<?php

namespace App\Services;

use App\Models\Contact;
use Illuminate\Pagination\LengthAwarePaginator;

class ContactService
{
    /**
     * Get paginated contacts with optional filters.
     */
    public function getContacts(array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Contact::query();

        // Busca global em tempo real
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('first_name', 'like', "%{$search}%")
                  ->orWhere('last_name', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%")
                  ->orWhere('company', 'like', "%{$search}%");
            });
        }

        // Filtro específico por nome
        if (!empty($filters['name'])) {
            $name = $filters['name'];
            $query->where(function ($q) use ($name) {
                $q->where('first_name', 'like', "%{$name}%")
                  ->orWhere('last_name', 'like', "%{$name}%");
            });
        }

        // Filtro específico por telefone
        if (!empty($filters['phone'])) {
            $query->where('phone', 'like', "%{$filters['phone']}%");
        }

        // Filtro específico por empresa
        if (!empty($filters['company'])) {
            $query->where('company', 'like', "%{$filters['company']}%");
        }

        // Ordenação
        $sortField = $filters['sort'] ?? 'first_name';
        $sortDirection = $filters['direction'] ?? 'asc';

        // Permitir apenas colunas válidas para ordenação
        $allowedSorts = ['first_name', 'last_name', 'created_at', 'company'];
        if (in_array($sortField, $allowedSorts)) {
            $query->orderBy($sortField, $sortDirection === 'desc' ? 'desc' : 'asc');
        }

        return $query->paginate($perPage)->withQueryString();
    }

    /**
     * Create a new contact.
     */
    public function createContact(array $data): Contact
    {
        // Aqui no futuro dispararemos o evento ContactCreated (Fase 4)
        return Contact::create($data);
    }

    /**
     * Update an existing contact.
     */
    public function updateContact(Contact $contact, array $data): Contact
    {
        $contact->update($data);
        // Aqui no futuro dispararemos o evento ContactUpdated (Fase 4)
        
        return $contact;
    }

    /**
     * Delete a contact.
     */
    public function deleteContact(Contact $contact): bool
    {
        $deleted = $contact->delete();
        // Aqui no futuro dispararemos o evento ContactDeleted (Fase 4)
        
        return $deleted;
    }
}
