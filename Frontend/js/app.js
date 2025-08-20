class ProductManager {
    constructor() {
        this.apiUrl = 'http://localhost:8080';
        this.products = [];
        this.editingProductId = null;
        
        this.initializeElements();
        this.bindEvents();
        this.loadProducts();
    }

    initializeElements() {
        this.form = document.getElementById('product-form');
        this.formTitle = document.getElementById('form-title');
        this.submitBtn = document.getElementById('submit-btn');
        this.cancelBtn = document.getElementById('cancel-btn');
        this.productIdInput = document.getElementById('product-id');
        this.nombreInput = document.getElementById('nombre');
        this.descripcionInput = document.getElementById('descripcion');
        this.precioInput = document.getElementById('precio');
        this.productsTable = document.getElementById('products-table');
        this.productsTbody = document.getElementById('products-tbody');
        this.loading = document.getElementById('loading');
        this.errorMessage = document.getElementById('error-message');
        this.confirmModal = document.getElementById('confirm-modal');
        this.confirmDeleteBtn = document.getElementById('confirm-delete');
        this.cancelDeleteBtn = document.getElementById('cancel-delete');
        this.notification = document.getElementById('notification');
    }

    bindEvents() {
        this.form.addEventListener('submit', (e) => this.handleFormSubmit(e));
        this.cancelBtn.addEventListener('click', () => this.cancelEdit());
        this.confirmDeleteBtn.addEventListener('click', () => this.confirmDelete());
        this.cancelDeleteBtn.addEventListener('click', () => this.hideConfirmModal());
    }

    async makeRequest(url, options = {}) {
        try {
            const response = await fetch(url, {
                headers: {
                    'Content-Type': 'application/json',
                    ...options.headers
                },
                ...options
            });

            const data = await response.json();
            
            if (!response.ok) {
                throw new Error(data.message || `HTTP error! status: ${response.status}`);
            }
            
            return data;
        } catch (error) {
            console.error('Request failed:', error);
            throw error;
        }
    }

    async loadProducts() {
        try {
            this.showLoading();
            this.hideError();
            
            const response = await this.makeRequest(`${this.apiUrl}/productos`);
            this.products = response.data || [];
            this.renderProducts();
            
        } catch (error) {
            console.error('Error loading products:', error);
            this.showError('Error al cargar los productos: ' + error.message);
        } finally {
            this.hideLoading();
        }
    }

    // renderizo todo del lado del cliente   
    renderProducts() {
        if (this.products.length === 0) {
            this.productsTbody.innerHTML = `
                <tr>
                    <td colspan="7" style="text-align: center; padding: 40px; color: #6c757d;">
                        No hay productos disponibles
                    </td>
                </tr>
            `;
            return;
        }

        this.productsTbody.innerHTML = this.products.map(product => `
            <tr>
                <td>${product.id}</td>
                <td>${this.escapeHtml(product.nombre)}</td>
                <td>${this.escapeHtml(product.descripcion)}</td>
                <td>$${this.formatNumber(product.precio)}</td>
                <td>$${this.formatNumber(product.precio_usd)} USD</td>
                <td>${this.formatDate(product.created_at)}</td>
                <td>
                    <button class="btn-edit" onclick="productManager.editProduct(${product.id})">
                        Editar
                    </button>
                    <button class="btn-delete" onclick="productManager.deleteProduct(${product.id})">
                        Eliminar
                    </button>
                </td>
            </tr>
        `).join('');
    }

    async handleFormSubmit(e) {
        e.preventDefault();
        
        const formData = {
            nombre: this.nombreInput.value.trim(),
            descripcion: this.descripcionInput.value.trim(),
            precio: parseFloat(this.precioInput.value)
        };

        if (!this.validateForm(formData)) {
            return;
        }

        try {
            this.submitBtn.disabled = true;
            this.submitBtn.textContent = 'Guardando...';

            if (this.editingProductId) {
                await this.updateProduct(this.editingProductId, formData);
                this.showNotification('Producto actualizado correctamente', 'success');
            } else {
                await this.createProduct(formData);
                this.showNotification('Producto creado correctamente', 'success');
            }

            this.resetForm();
            this.loadProducts();

        } catch (error) {
            console.error('Error saving product:', error);
            this.showNotification('Error al guardar el producto: ' + error.message, 'error');
        } finally {
            this.submitBtn.disabled = false;
            this.submitBtn.textContent = this.editingProductId ? 'Actualizar Producto' : 'Agregar Producto';
        }
    }

    validateForm(data) {
        if (!data.nombre) {
            this.showNotification('El nombre es obligatorio', 'error');
            return false;
        }
        if (!data.descripcion) {
            this.showNotification('La descripción es obligatoria', 'error');
            return false;
        }
        if (!data.precio || data.precio <= 0) {
            this.showNotification('El precio debe ser mayor a 0', 'error');
            return false;
        }
        return true;
    }

    async createProduct(data) {
        const response = await this.makeRequest(`${this.apiUrl}/productos`, {
            method: 'POST',
            body: JSON.stringify(data)
        });
        return response;
    }

    async updateProduct(id, data) {
        const response = await this.makeRequest(`${this.apiUrl}/productos/${id}`, {
            method: 'PUT',
            body: JSON.stringify(data)
        });
        return response;
    }

    async editProduct(id) {
        const product = this.products.find(p => p.id == id);
        if (!product) {
            this.showNotification('Producto no encontrado', 'error');
            return;
        }

        this.editingProductId = id;
        this.productIdInput.value = id;
        this.nombreInput.value = product.nombre;
        this.descripcionInput.value = product.descripcion;
        this.precioInput.value = product.precio;

        this.formTitle.textContent = 'Editar Producto';
        this.submitBtn.textContent = 'Actualizar Producto';
        this.cancelBtn.style.display = 'inline-block';

        // Scroll al formulario
        this.form.scrollIntoView({ behavior: 'smooth' });
    }

    cancelEdit() {
        this.resetForm();
    }

    resetForm() {
        this.editingProductId = null;
        this.productIdInput.value = '';
        this.form.reset();
        this.formTitle.textContent = 'Agregar Producto';
        this.submitBtn.textContent = 'Agregar Producto';
        this.cancelBtn.style.display = 'none';
    }

    deleteProduct(id) {
        this.productToDelete = id;
        this.showConfirmModal();
    }

    async confirmDelete() {
        if (!this.productToDelete) return;

        try {
            await this.makeRequest(`${this.apiUrl}/productos/${this.productToDelete}`, {
                method: 'DELETE'
            });

            this.showNotification('Producto eliminado correctamente', 'success');
            this.loadProducts();
            
            if (this.editingProductId == this.productToDelete) {
                this.resetForm();
            }

        } catch (error) {
            console.error('Error deleting product:', error);
            this.showNotification('Error al eliminar el producto: ' + error.message, 'error');
        } finally {
            this.hideConfirmModal();
        }
    }

    showConfirmModal() {
        this.confirmModal.style.display = 'flex';
    }

    hideConfirmModal() {
        this.confirmModal.style.display = 'none';
        this.productToDelete = null;
    }

    showLoading() {
        this.loading.style.display = 'block';
        this.productsTable.style.display = 'none';
    }

    hideLoading() {
        this.loading.style.display = 'none';
        this.productsTable.style.display = 'table';
    }

    showError(message) {
        this.errorMessage.textContent = message;
        this.errorMessage.style.display = 'block';
    }

    hideError() {
        this.errorMessage.style.display = 'none';
    }

    showNotification(message, type = 'info') {
        this.notification.textContent = message;
        this.notification.className = `notification ${type}`;
        this.notification.style.display = 'block';

        setTimeout(() => {
            this.notification.style.display = 'none';
        }, 4000);
    }

    escapeHtml(text) {
        const map = {
            '&': '&amp;',
            '<': '&lt;',
            '>': '&gt;',
            '"': '&quot;',
            "'": '&#039;'
        };
        return text.replace(/[&<>"']/g, m => map[m]);
    }

    formatNumber(number) {
        return new Intl.NumberFormat('es-AR', {
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }).format(number);
    }

    formatDate(dateString) {
        const date = new Date(dateString);
        return new Intl.DateTimeFormat('es-AR', {
            year: 'numeric',
            month: '2-digit',
            day: '2-digit',
            hour: '2-digit',
            minute: '2-digit'
        }).format(date);
    }
}

// Inicializar la aplicación cuando se carga la página
document.addEventListener('DOMContentLoaded', () => {
    window.productManager = new ProductManager();
}); 