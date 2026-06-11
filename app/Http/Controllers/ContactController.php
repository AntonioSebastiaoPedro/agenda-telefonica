<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Services\ContactService;
use App\Http\Requests\ContactStoreRequest;
use App\Http\Requests\ContactUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ContactController extends Controller
{
    protected ContactService $contactService;

    public function __construct(ContactService $contactService)
    {
        $this->contactService = $contactService;
    }

    /**
     * Retorna uma lista paginada dos contatos.
     */
    public function index(Request $request): JsonResponse
    {
        $contacts = $this->contactService->getContacts($request->all());
        return response()->json($contacts);
    }

    /**
     * Armazena um novo contato no banco de dados.
     */
    public function store(ContactStoreRequest $request): JsonResponse
    {
        $contact = $this->contactService->createContact($request->validated());
        return response()->json([
            'message' => 'Contato criado com sucesso!',
            'contact' => $contact
        ], 201);
    }

    /**
     * Exibe os detalhes de um contato específico.
     */
    public function show(Contact $contact): JsonResponse
    {
        return response()->json($contact);
    }

    /**
     * Atualiza um contato existente no banco de dados.
     */
    public function update(ContactUpdateRequest $request, Contact $contact): JsonResponse
    {
        $updatedContact = $this->contactService->updateContact($contact, $request->validated());
        return response()->json([
            'message' => 'Contato atualizado com sucesso!',
            'contact' => $updatedContact
        ]);
    }

    /**
     * Remove o contato especificado do banco de dados.
     */
    public function destroy(Contact $contact): JsonResponse
    {
        $this->contactService->deleteContact($contact);
        return response()->json([
            'message' => 'Contato excluído com sucesso!'
        ]);
    }
}
