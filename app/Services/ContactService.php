<?php

namespace App\Services;

use App\Models\Contact;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Events\ContactCreated;
use App\Events\ContactUpdated;
use App\Events\ContactDeleted;

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
        $contact = Contact::create($data);
        ContactCreated::dispatch($contact);
        
        return $contact;
    }

    /**
     * Update an existing contact.
     */
    public function updateContact(Contact $contact, array $data): Contact
    {
        $contact->update($data);
        ContactUpdated::dispatch($contact);
        
        return $contact;
    }

    /**
     * Delete a contact.
     */
    public function deleteContact(Contact $contact): bool
    {
        $id = $contact->id;
        $deleted = $contact->delete();
        
        if ($deleted) {
            ContactDeleted::dispatch($id);
        }
        
        return $deleted;
    }
}
