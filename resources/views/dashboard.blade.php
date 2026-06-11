<x-layouts.app title="Agenda Telefónica - Dashboard">
    
    <div x-data="contactsApp()" x-init="init()" class="space-y-6">

        <!-- Notificações Toast -->
        <div class="fixed top-4 right-4 z-50 space-y-2">
            <template x-for="toast in toasts" :key="toast.id">
                <div x-show="true" 
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 transform translate-x-8"
                     x-transition:enter-end="opacity-100 transform translate-x-0"
                     x-transition:leave="transition ease-in duration-200"
                     x-transition:leave-start="opacity-100 transform translate-x-0"
                     x-transition:leave-end="opacity-0 transform translate-x-8"
                     class="px-4 py-3 rounded shadow-lg flex items-center gap-3 backdrop-blur-md"
                     :class="toast.type === 'success' ? 'bg-green-50 text-green-800 border-l-4 border-green-500' : 'bg-red-50 text-red-800 border-l-4 border-red-500'">
                    <span x-text="toast.message"></span>
                </div>
            </template>
        </div>

        <div class="flex flex-col md:flex-row justify-between items-center gap-4">
            <div>
                <h2 class="text-2xl font-bold text-gray-900 tracking-tight">Agenda Telefónica</h2>
                <p class="text-sm text-gray-500">Gerencie seus contatos de forma eficiente.</p>
            </div>
            <button @click="openModal('create')" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-lg text-sm font-medium transition-all shadow-md hover:shadow-lg flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Novo Contato
            </button>
        </div>

        <!-- Estatísticas Dinâmicas -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                <div class="absolute -right-4 -top-4 w-24 h-24 bg-blue-50 rounded-full group-hover:scale-110 transition-transform"></div>
                <div class="flex items-center relative z-10">
                    <div class="p-3 rounded-xl bg-blue-100/50 text-blue-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Total de Contatos</p>
                        <p class="text-2xl font-bold text-gray-900" x-text="stats.total"></p>
                    </div>
                </div>
            </div>
            
            <div class="bg-white rounded-xl border border-gray-100 p-6 shadow-sm hover:shadow-md transition-shadow relative overflow-hidden group">
                 <div class="absolute -right-4 -top-4 w-24 h-24 bg-green-50 rounded-full group-hover:scale-110 transition-transform"></div>
                <div class="flex items-center relative z-10">
                    <div class="p-3 rounded-xl bg-green-100/50 text-green-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm font-medium text-gray-500">Recentes</p>
                        <p class="text-2xl font-bold text-gray-900" x-text="stats.recent"></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtros e Busca -->
        <div class="bg-white p-4 rounded-xl border border-gray-100 shadow-sm flex flex-col md:flex-row gap-4 items-center justify-between">
            <div class="relative w-full md:w-96">
                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                <input x-model.debounce.500ms="search" type="text" class="block w-full pl-10 pr-3 py-2 border border-gray-200 rounded-lg leading-5 bg-gray-50 placeholder-gray-400 focus:outline-none focus:bg-white focus:ring-2 focus:ring-blue-500 focus:border-blue-500 sm:text-sm transition-colors" placeholder="Buscar contatos...">
            </div>
            <!-- Pode-se adicionar filtros extras de dropdown aqui futuramente -->
        </div>

        <!-- Tabela -->
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" @click="sortBy('first_name')">
                                Contato
                                <span x-show="sort === 'first_name'" x-text="direction === 'asc' ? '↑' : '↓'" class="ml-1"></span>
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Telefones
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider cursor-pointer hover:bg-gray-100" @click="sortBy('company')">
                                Empresa
                                <span x-show="sort === 'company'" x-text="direction === 'asc' ? '↑' : '↓'" class="ml-1"></span>
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">
                                Ações
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-100">
                        
                        <!-- Loading State -->
                        <tr x-show="loading">
                            <td colspan="4" class="px-6 py-12 text-center">
                                <svg class="animate-spin h-8 w-8 text-blue-500 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                <p class="mt-2 text-sm text-gray-500">Carregando contatos...</p>
                            </td>
                        </tr>

                        <!-- Empty State -->
                        <tr x-show="!loading && contacts.length === 0">
                            <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                                </div>
                                <p class="text-base font-medium text-gray-900">Nenhum contato encontrado.</p>
                                <p class="text-sm text-gray-500 mt-1">Tente ajustar a sua busca ou adicione um novo contato.</p>
                            </td>
                        </tr>

                        <template x-for="contact in contacts" :key="contact.id">
                            <tr class="hover:bg-gray-50/50 transition-colors group">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div class="flex-shrink-0 h-10 w-10">
                                            <div class="h-10 w-10 rounded-full bg-gradient-to-br from-blue-100 to-blue-200 flex items-center justify-center text-blue-700 font-bold shadow-inner">
                                                <span x-text="contact.first_name.charAt(0)"></span>
                                            </div>
                                        </div>
                                        <div class="ml-4">
                                            <div class="text-sm font-medium text-gray-900" x-text="contact.first_name + ' ' + (contact.last_name || '')"></div>
                                            <div class="text-sm text-gray-500" x-text="contact.email || 'Sem e-mail'"></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900 font-medium" x-text="contact.phone"></div>
                                    <div class="text-xs text-gray-500" x-text="contact.secondary_phone || '---'"></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800" x-text="contact.company || 'N/A'"></span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <div class="opacity-0 group-hover:opacity-100 transition-opacity flex justify-end gap-2">
                                        <button @click="openModal('view', contact)" class="text-blue-600 hover:text-blue-900 p-1 hover:bg-blue-50 rounded"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg></button>
                                        <button @click="openModal('edit', contact)" class="text-indigo-600 hover:text-indigo-900 p-1 hover:bg-indigo-50 rounded"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg></button>
                                        <button @click="confirmDelete(contact.id)" class="text-red-600 hover:text-red-900 p-1 hover:bg-red-50 rounded"><svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg></button>
                                    </div>
                                </td>
                            </tr>
                        </template>
                    </tbody>
                </table>
            </div>
            
            <!-- Paginação -->
            <div class="bg-white px-4 py-3 border-t border-gray-200 flex items-center justify-between sm:px-6">
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Página <span class="font-medium" x-text="pagination.current_page"></span> de <span class="font-medium" x-text="pagination.last_page"></span>
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <button @click="changePage(pagination.current_page - 1)" :disabled="!pagination.prev_page_url" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50">
                                Anterior
                            </button>
                            <button @click="changePage(pagination.current_page + 1)" :disabled="!pagination.next_page_url" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 disabled:opacity-50">
                                Próxima
                            </button>
                        </nav>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Único (Criação/Edição/Visualização) -->
        <div x-show="modal.isOpen" class="fixed z-50 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true" style="display: none;">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                
                <div x-show="modal.isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" class="fixed inset-0 bg-gray-500/75 backdrop-blur-sm transition-opacity" @click="closeModal()"></div>

                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

                <div x-show="modal.isOpen" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100" x-transition:leave="ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100" x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95" class="relative z-10 inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg w-full">
                    
                    <form @submit.prevent="saveContact">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            
                            <div class="flex justify-between items-center mb-5 border-b border-gray-100 pb-3">
                                <h3 class="text-lg leading-6 font-semibold text-gray-900" id="modal-title" x-text="modal.title"></h3>
                                <button type="button" @click="closeModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                    <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </div>

                            <div class="space-y-4">
                                <!-- Erros gerais -->
                                <div x-show="formErrors.general" class="bg-red-50 text-red-600 p-3 rounded text-sm" x-text="formErrors.general"></div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Nome <span class="text-red-500">*</span></label>
                                        <input type="text" x-model="form.first_name" :disabled="modal.mode === 'view'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 border" required>
                                        <p x-show="formErrors.first_name" class="text-red-500 text-xs mt-1" x-text="formErrors.first_name"></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Sobrenome</label>
                                        <input type="text" x-model="form.last_name" :disabled="modal.mode === 'view'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 border">
                                    </div>
                                </div>

                                <div class="grid grid-cols-2 gap-4">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Telefone <span class="text-red-500">*</span></label>
                                        <input type="text" x-model="form.phone" :disabled="modal.mode === 'view'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 border" required>
                                        <p x-show="formErrors.phone" class="text-red-500 text-xs mt-1" x-text="formErrors.phone"></p>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Telefone Secundário</label>
                                        <input type="text" x-model="form.secondary_phone" :disabled="modal.mode === 'view'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 border">
                                    </div>
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">E-mail</label>
                                    <input type="email" x-model="form.email" :disabled="modal.mode === 'view'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 border">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Empresa</label>
                                    <input type="text" x-model="form.company" :disabled="modal.mode === 'view'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 border">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Endereço</label>
                                    <input type="text" x-model="form.address" :disabled="modal.mode === 'view'" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 border">
                                </div>

                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Observações</label>
                                    <textarea x-model="form.notes" :disabled="modal.mode === 'view'" rows="3" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm px-3 py-2 border"></textarea>
                                </div>
                            </div>
                        </div>
                        
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse border-t border-gray-100">
                            <template x-if="modal.mode !== 'view'">
                                <button type="submit" :disabled="saving" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm disabled:opacity-50">
                                    <span x-show="!saving">Salvar</span>
                                    <span x-show="saving">Salvando...</span>
                                </button>
                            </template>
                            <button type="button" @click="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                <span x-text="modal.mode === 'view' ? 'Fechar' : 'Cancelar'"></span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('contactsApp', () => ({
                contacts: [],
                loading: true,
                saving: false,
                search: '',
                sort: 'first_name',
                direction: 'asc',
                pagination: {
                    current_page: 1,
                    last_page: 1,
                    next_page_url: null,
                    prev_page_url: null,
                },
                stats: {
                    total: 0,
                    recent: 0
                },
                toasts: [],
                toastIdCounter: 1,
                
                modal: {
                    isOpen: false,
                    mode: 'create', // create, edit, view
                    title: 'Novo Contato',
                },
                
                form: {
                    id: null,
                    first_name: '',
                    last_name: '',
                    phone: '',
                    secondary_phone: '',
                    email: '',
                    company: '',
                    address: '',
                    notes: ''
                },
                formErrors: {},

                init() {
                    this.fetchContacts();
                    this.listenToWebsockets();

                    // Watchers
                    this.$watch('search', () => {
                        this.pagination.current_page = 1;
                        this.fetchContacts();
                    });
                },

                async fetchContacts() {
                    this.loading = true;
                    try {
                        const url = new URL('/api/contacts', window.location.origin);
                        if (this.search) url.searchParams.append('search', this.search);
                        url.searchParams.append('sort', this.sort);
                        url.searchParams.append('direction', this.direction);
                        url.searchParams.append('page', this.pagination.current_page);

                        const response = await fetch(url, {
                            headers: { 'Accept': 'application/json' }
                        });
                        const data = await response.json();
                        
                        this.contacts = data.data;
                        this.pagination = {
                            current_page: data.current_page,
                            last_page: data.last_page,
                            next_page_url: data.next_page_url,
                            prev_page_url: data.prev_page_url,
                        };
                        this.stats.total = data.total;
                        
                        // Fake recents (normally from another endpoint)
                        this.stats.recent = Math.min(15, data.total);
                        
                    } catch (error) {
                        this.showToast('Erro ao carregar contatos.', 'error');
                    } finally {
                        this.loading = false;
                    }
                },

                sortBy(field) {
                    if (this.sort === field) {
                        this.direction = this.direction === 'asc' ? 'desc' : 'asc';
                    } else {
                        this.sort = field;
                        this.direction = 'asc';
                    }
                    this.fetchContacts();
                },

                changePage(page) {
                    if (page < 1 || page > this.pagination.last_page) return;
                    this.pagination.current_page = page;
                    this.fetchContacts();
                },

                resetForm() {
                    this.form = {
                        id: null,
                        first_name: '',
                        last_name: '',
                        phone: '',
                        secondary_phone: '',
                        email: '',
                        company: '',
                        address: '',
                        notes: ''
                    };
                    this.formErrors = {};
                },

                openModal(mode, contact = null) {
                    this.resetForm();
                    this.modal.mode = mode;
                    this.modal.isOpen = true;

                    if (mode === 'create') {
                        this.modal.title = 'Novo Contato';
                    } else if (mode === 'edit') {
                        this.modal.title = 'Editar Contato';
                        this.form = { ...contact };
                    } else if (mode === 'view') {
                        this.modal.title = 'Detalhes do Contato';
                        this.form = { ...contact };
                    }
                },

                closeModal() {
                    this.modal.isOpen = false;
                },

                async saveContact() {
                    this.saving = true;
                    this.formErrors = {};
                    
                    try {
                        const isEdit = this.modal.mode === 'edit';
                        const url = isEdit ? `/api/contacts/${this.form.id}` : '/api/contacts';
                        const method = isEdit ? 'PUT' : 'POST';
                        
                        // Add CSRF Token
                        const token = document.head.querySelector('meta[name="csrf-token"]')?.content;

                        const response = await fetch(url, {
                            method: method,
                            headers: {
                                'Content-Type': 'application/json',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': token || ''
                            },
                            body: JSON.stringify(this.form)
                        });

                        const data = await response.json();

                        if (!response.ok) {
                            if (response.status === 422) {
                                for (let field in data.errors) {
                                    this.formErrors[field] = data.errors[field][0];
                                }
                            } else {
                                this.formErrors.general = data.message || 'Erro desconhecido.';
                            }
                            return;
                        }

                        this.showToast(data.message || 'Salvo com sucesso!', 'success');
                        this.closeModal();
                        
                        // O Realtime irá atualizar a lista, mas fazemos um refresh preventivo para ordenar corretamente
                        // Se quisermos apenas confiar no realtime:
                        // if (!window.Echo) this.fetchContacts();
                        
                    } catch (error) {
                        this.formErrors.general = 'Falha na conexão com o servidor.';
                    } finally {
                        this.saving = false;
                    }
                },

                async confirmDelete(id) {
                    if (!confirm('Tem certeza que deseja excluir este contato?')) return;
                    
                    try {
                        const token = document.head.querySelector('meta[name="csrf-token"]')?.content;
                        const response = await fetch(`/api/contacts/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': token || ''
                            }
                        });

                        if (response.ok) {
                            this.showToast('Contato excluído!', 'success');
                        } else {
                            this.showToast('Erro ao excluir contato.', 'error');
                        }
                    } catch (error) {
                        this.showToast('Falha na exclusão.', 'error');
                    }
                },

                showToast(message, type = 'success') {
                    const id = this.toastIdCounter++;
                    this.toasts.push({ id, message, type });
                    setTimeout(() => {
                        this.toasts = this.toasts.filter(t => t.id !== id);
                    }, 4000);
                },

                listenToWebsockets() {
                    if (typeof window.Echo !== 'undefined') {
                        // Previne listeners duplicados caso o Vite faça Hot Module Replacement (HMR)
                        window.Echo.leaveChannel('contacts');
                        
                        window.Echo.channel('contacts')
                            .listen('ContactCreated', (e) => {
                                // Atualiza a lista silenciosamente
                                this.fetchContacts();
                            })
                            .listen('ContactUpdated', (e) => {
                                const index = this.contacts.findIndex(c => c.id === e.contact.id);
                                if (index !== -1) {
                                    this.contacts[index] = e.contact;
                                }
                            })
                            .listen('ContactDeleted', (e) => {
                                this.contacts = this.contacts.filter(c => c.id !== e.contactId);
                                this.stats.total--;
                            });
                    } else {
                        console.warn('Laravel Echo não está inicializado.');
                    }
                }
            }));
        });
    </script>
    @endpush
</x-layouts.app>
