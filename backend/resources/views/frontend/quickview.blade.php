<div class="quickview-modal" id="quickviewModal">
    <div class="quickview-modal-overlay"></div>
    <div class="quickview-modal-content">
        <button class="quickview-modal-close" type="button">
            <i class="lnr lnr-cross"></i>
        </button>
        <div class="quickview-modal-body" id="quickviewContent">
            <div class="quickview-loading">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.quickview-modal {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    overflow-y: auto;
}

.quickview-modal.active {
    display: flex;
    align-items: center;
    justify-content: center;
}

.quickview-modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.7);
    backdrop-filter: blur(2px);
}

.quickview-modal-content {
    position: relative;
    background: #fff;
    border-radius: 8px;
    max-width: 900px;
    width: 90%;
    max-height: 90vh;
    overflow-y: auto;
    margin: 20px auto;
    box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
    animation: quickviewSlideIn 0.3s ease-out;
}

@keyframes quickviewSlideIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}

.quickview-modal-close {
    position: absolute;
    top: 15px;
    right: 15px;
    background: transparent;
    border: none;
    font-size: 24px;
    color: #333;
    cursor: pointer;
    z-index: 10;
    width: 40px;
    height: 40px;
    display: flex;
    align-items: center;
    justify-content: center;
    border-radius: 50%;
    transition: all 0.3s;
}

.quickview-modal-close:hover {
    background: #f5f5f5;
    color: #000;
}

.quickview-modal-body {
    padding: 40px;
}

.quickview-loading {
    display: flex;
    justify-content: center;
    align-items: center;
    min-height: 300px;
}

.quickview-product {
    display: flex;
    gap: 30px;
}

.quickview-product-image {
    flex: 1;
    max-width: 400px;
}

.quickview-product-image img {
    width: 100%;
    height: auto;
    border-radius: 8px;
}

.quickview-product-info {
    flex: 1;
}

.quickview-product-title {
    font-size: 24px;
    font-weight: 600;
    margin-bottom: 15px;
    color: #333;
}

.quickview-product-price {
    margin-bottom: 20px;
}

.quickview-product-price .price {
    font-size: 28px;
    font-weight: 700;
    color: #007bff;
}

.quickview-product-price .oldprice {
    font-size: 20px;
    color: #999;
    text-decoration: line-through;
    margin-right: 10px;
}

.quickview-product-price .discount-badge {
    display: inline-block;
    background: #dc3545;
    color: white;
    padding: 5px 10px;
    border-radius: 4px;
    font-size: 14px;
    margin-left: 10px;
}

.quickview-product-description {
    margin-bottom: 20px;
    color: #666;
    line-height: 1.6;
}

.quickview-product-stock {
    margin-bottom: 20px;
}

.quickview-product-stock .badge {
    padding: 8px 15px;
    border-radius: 4px;
    font-size: 14px;
}

.quickview-product-actions {
    display: flex;
    gap: 15px;
    margin-top: 30px;
}

.quickview-product-actions .btn {
    flex: 1;
    padding: 12px 20px;
    border-radius: 4px;
    text-align: center;
    text-decoration: none;
    font-weight: 600;
    transition: all 0.3s;
}

.quickview-product-actions .btn-primary {
    background: #007bff;
    color: white;
    border: none;
}

.quickview-product-actions .btn-primary:hover {
    background: #0056b3;
}

.quickview-product-actions .btn-outline {
    background: transparent;
    color: #007bff;
    border: 2px solid #007bff;
}

.quickview-product-actions .btn-outline:hover {
    background: #007bff;
    color: white;
}

@media (max-width: 768px) {
    .quickview-product {
        flex-direction: column;
    }
    
    .quickview-product-image {
        max-width: 100%;
    }
    
    .quickview-modal-content {
        width: 95%;
        margin: 10px auto;
    }
    
    .quickview-modal-body {
        padding: 20px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('quickviewModal');
    const modalContent = document.getElementById('quickviewContent');
    const closeBtn = document.querySelector('.quickview-modal-close');
    const overlay = document.querySelector('.quickview-modal-overlay');
    
    // Close modal handlers
    function closeModal() {
        modal.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    closeBtn.addEventListener('click', closeModal);
    overlay.addEventListener('click', closeModal);
    
    // ESC key to close
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && modal.classList.contains('active')) {
            closeModal();
        }
    });
    
    // Handle quickview triggers
    document.addEventListener('click', function(e) {
        const trigger = e.target.closest('.quickview-trigger');
        if (trigger) {
            e.preventDefault();
            let productId = trigger.getAttribute('data-product-id') ||
                trigger.closest('[data-product-id]')?.getAttribute('data-product-id');
            if (!productId) {
                const href = trigger.getAttribute('href') || '';
                const m = href.match(/\/details\/(\d+)/);
                if (m) {
                    productId = m[1];
                }
            }

            if (productId) {
                openQuickView(productId);
            }
        }
    });
    
    function openQuickView(productId) {
        modal.classList.add('active');
        document.body.style.overflow = 'hidden';
        
        // Show loading
        modalContent.innerHTML = `
            <div class="quickview-loading">
                <div class="spinner-border" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        `;
        
        // Fetch product data
        fetch(`/quickview/${productId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                renderQuickView(data);
            } else {
                modalContent.innerHTML = `
                    <div class="alert alert-danger">
                        ${data.message || 'Product not found'}
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            modalContent.innerHTML = `
                <div class="alert alert-danger">
                    An error occurred while loading the product.
                </div>
            `;
        });
    }
    
    function renderQuickView(data) {
        const product = data.product;
        const locale = data.locale || 'en';
        const name = locale === 'ar' ? product.name_ar : product.name_en;
        const description = locale === 'ar' ? product.description_ar : product.description_en;
        const categoryName = locale === 'ar' ? product.category.name_ar : product.category.name_en;
        
        const stockBadge = product.is_available && product.quantity > 0
            ? `<span class="badge badge-success">${locale === 'ar' ? 'متوفر' : 'In Stock'}</span>`
            : `<span class="badge badge-danger">${locale === 'ar' ? 'غير متوفر' : 'Out of Stock'}</span>`;
        
        const priceHtml = product.discount > 0
            ? `
                <del class="oldprice">${product.price} KWD</del>
                <span class="price">${product.price_after_discount} KWD</span>
                <span class="discount-badge">-${product.discount_percentage}%</span>
            `
            : `<span class="price">${product.price} KWD</span>`;
        
        modalContent.innerHTML = `
            <div class="quickview-product">
                <div class="quickview-product-image">
                    <img src="${product.photo}" alt="${name}">
                </div>
                <div class="quickview-product-info">
                    <h2 class="quickview-product-title">${name}</h2>
                    <div class="quickview-product-price">
                        ${priceHtml}
                    </div>
                    <div class="quickview-product-stock">
                        ${stockBadge}
                    </div>
                    <div class="quickview-product-description">
                        ${description ? description.substring(0, 200) + (description.length > 200 ? '...' : '') : ''}
                    </div>
                    <div class="quickview-product-actions">
                        <a href="/details/${product.id}" class="btn btn-outline">
                            ${locale === 'ar' ? 'عرض التفاصيل' : 'View Details'}
                        </a>
                        <button class="btn btn-primary" onclick="addToCartQuickView(${product.id}, '${name.replace(/'/g, "\\'")}', ${product.price}, '${product.photo}')">
                            <i class="lnr lnr-cart"></i>
                            ${locale === 'ar' ? 'أضف إلى السلة' : 'Add to Cart'}
                        </button>
                    </div>
                </div>
            </div>
        `;
    }
    
    // Make function globally available
    window.openQuickView = openQuickView;
    window.addToCartQuickView = function(productId, productName, productPrice, productPhoto) {
        // Use fetch API to add to cart (works with Livewire)
        const formData = new FormData();
        formData.append('_token', document.querySelector('meta[name="csrf-token"]')?.content || '');
        formData.append('id', productId);
        formData.append('name', productName);
        formData.append('price', productPrice);
        formData.append('quantity', 1);
        formData.append('photo', productPhoto);
        
        fetch('/cart', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (response.ok) {
                // Close modal
                closeModal();
                // Trigger cart update event for Livewire
                window.dispatchEvent(new CustomEvent('cart-updated'));
                // Show success alert
                if (typeof window.showAlert === 'function' || typeof $ !== 'undefined') {
                    const locale = document.documentElement.lang || 'en';
                    const message = locale === 'ar' ? 'تم إضافة المنتج إلى السلة بنجاح' : 'Product added to cart successfully';
                    if (typeof $ !== 'undefined' && $.event) {
                        $(window).trigger('show-alert', [{type: 'success', message: message}]);
                    } else if (window.dispatchEvent) {
                        window.dispatchEvent(new CustomEvent('show-alert', {detail: [{type: 'success', message: message}]}));
                    }
                }
                // Reload cart counter if Livewire component exists
                if (typeof Livewire !== 'undefined') {
                    Livewire.emit('cart-updated');
                }
            } else {
                throw new Error('Failed to add product to cart');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            const locale = document.documentElement.lang || 'en';
            const message = locale === 'ar' ? 'حدث خطأ أثناء إضافة المنتج إلى السلة' : 'Error adding product to cart';
            alert(message);
        });
    };
});
</script>
