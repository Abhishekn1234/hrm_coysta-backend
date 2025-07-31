import React, { useState, useEffect } from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import '@fortawesome/fontawesome-free/css/all.min.css';
import Main from './Main';
import axios from 'axios';

// ✅ FULL MODAL WITH LEAD FORM FIELDS
function AddProductModal({ show, onClose, onProductAdded}) {
  const [formData, setFormData] = useState({
    name: '',
    hsn: '',
    stock_category: '',
    unit: '',
    worth: '',
    vendor: '',
    sub_category: '',
  });

  const [message, setMessage] = useState('');
  const [messageType, setMessageType] = useState(''); // 'success' | 'error'

  useEffect(() => {
    if (show) {
      setFormData({
        name: '',
        hsn: '',
        stock_category: '',
        unit: '',
        worth: '',
        vendor: '',
        sub_category: '',
      });
      setMessage('');
      setMessageType('');
    }
  }, [show]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = async (e) => {
    e.preventDefault();
    setMessage('');
    setMessageType('');

    try {
      const response = await axios.post('/api/v1/inventories/inventory_save', formData);
      setMessage('Product saved successfully!');
      setMessageType('success');

      // setTimeout(() => {
      //   onClose();
      // }, 1500);
      setTimeout(() => {
        onClose();           // Close the modal
        if (onProductAdded) onProductAdded();  // Notify parent to refresh data
      }, 1500);
    } catch (error) {
      console.error('Error saving product:', error);
      setMessage('Failed to save product. Please try again.');
      setMessageType('error');
    }
  };

  if (!show) return null;

  return (
    <>
      <div
        className="modal-backdrop fade show"
        style={{ zIndex: 1040, backgroundColor: 'rgba(0,0,0,0.5)' }}
        onClick={onClose}
      ></div>

      <div className="modal d-block" tabIndex="-1" role="dialog" style={{ zIndex: 1050 }}>
        <div
          className="modal-dialog modal-dialog-centered"
          role="document"
          style={{ maxWidth: '400px' }}
        >
          <div className="modal-content" style={{ maxHeight: '80vh', overflowY: 'auto' }}>
            <form onSubmit={handleSubmit}>
              <div className="modal-header">
                <h5 className="modal-title">Add New Product</h5>
                <button
                  type="button"
                  className="btn-close"
                  aria-label="Close"
                  onClick={onClose}
                ></button>
              </div>
              <div className="modal-body">
                <div className="row g-2">
                  <div className="col-md-6">
                    <label className="form-label">Name</label>
                    <input
                      type="text"
                      className="form-control form-control-sm"
                      name="name"
                      value={formData.name}
                      onChange={handleChange}
                      required
                    />
                  </div>
                  <div className="col-md-6">
                    <label className="form-label">HSN Code</label>
                    <input
                      type="text"
                      className="form-control form-control-sm"
                      name="hsn"
                      value={formData.hsn}
                      onChange={handleChange}
                    />
                  </div>
                  <div className="col-md-6">
                    <label className="form-label">Stock Category</label>
                    <select
                      className="form-control form-control-sm"
                      name="stock_category"
                      value={formData.stock_category}
                      onChange={handleChange}
                    >
                      <option value="">Select Category</option>
                      <option value="electronics">Electronics</option>
                      <option value="furniture">Furniture</option>
                      <option value="machinery">Machinery</option>
                    </select>
                  </div>
                  <div className="col-md-6">
                    <label className="form-label">Unit</label>
                    <select
                      className="form-control form-control-sm"
                      name="unit"
                      value={formData.unit}
                      onChange={handleChange}
                    >
                      <option value="">Select Unit</option>
                      <option value="piece">Piece</option>
                      <option value="set">Set</option>
                      <option value="kg">Kg</option>
                      <option value="litre">Litre</option>
                    </select>
                  </div>

                  <div className="col-md-6">
                    <label className="form-label">Worth</label>
                    <input
                      type="text"
                      className="form-control form-control-sm"
                      name="worth"
                      value={formData.worth}
                      onChange={handleChange}
                    />
                  </div>
                  <div className="col-md-12">
                    <label className="form-label">Vendor</label>
                    <select
                      className="form-control form-control-sm"
                      name="vendor"
                      value={formData.vendor}
                      onChange={handleChange}
                    >
                      <option value="">Select Vendor</option>
                      <option value="vendorA">Vendor A</option>
                      <option value="vendorB">Vendor B</option>
                      <option value="vendorC">Vendor C</option>
                    </select>
                  </div>

                  <div className="col-md-12">
                    <label className="form-label">Sub Category</label>
                    <input
                      type="text"
                      className="form-control form-control-sm"
                      name="sub_category"
                      value={formData.sub_category}
                      onChange={handleChange}
                    />
                  </div>
                </div>

                {message && (
                  <div
                    className={`alert alert-${messageType === 'success' ? 'success' : 'danger'} mt-3 py-1 mb-0`}
                    role="alert"
                  >
                    {message}
                  </div>
                )}
              </div>
              <div className="modal-footer py-2">
                <button type="button" className="btn btn-secondary btn-sm" onClick={onClose}>
                  Cancel
                </button>
                <button type="submit" className="btn btn-primary btn-sm">
                  Save Product
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </>
  );
}

export default function InventoryManagement() {
  const [isModalOpen, setIsModalOpen] = useState(false);
  const [products, setProducts] = useState([]);

  const openModal = () => setIsModalOpen(true);
  const closeModal = () => setIsModalOpen(false);

  
  const fetchProducts = async () => {
    try {
      const response = await axios.get('/api/v1/inventories/inventories'); // Adjust your API URL here

      console.log('data got', response.data.categories);
      setProducts(response.data.categories);
    } catch (error) {
      console.error('Failed to fetch products:', error);
    }
  };
   useEffect(() => {
    fetchProducts();
    $('.js-navbar-vertical-aside-menu-link')
      .off('click')
      .on('click', function (e) {
        e.preventDefault();
        const $li = $(this).closest('li');
        const $submenu = $(this).next('.js-navbar-vertical-aside-submenu');
        $li.toggleClass('active');
        $submenu.slideToggle(200);
      });
  }, []);
  const handleProductAdded = () => {
    fetchProducts();  // Re-fetch the product list to update UI
  };
  // const totalProducts = products.length;
  // console.log("total numbers",totalProducts);
  // const inventoryValue = products.reduce((total, item) => total + (item.worth || 0), 0);
//  const inventoryValue = products.reduce((total, item) => {
//   const stock = parseFloat(item.stock_count ?? 0);
//   const worth = parseFloat(item.worth ?? 0);
//   return total + (stock * worth);
// }, 0);
const calculateInventoryStats = (products) => {
  let totalValue = 0;
  let totalCount = 0;
  let lowStockCount = 0;

  const calculateItemStats = (item) => {
    const stock = parseFloat(item.stock ?? 0);
    const worth = parseFloat(item.worth ?? 0);
    const minStock = parseFloat(item.minStock ?? 0);

    let itemValue = stock * worth;
    let itemCount = 1;
    let itemLowStockCount = stock < minStock ? 1 : 0;

    if (item.subItems && item.subItems.length > 0) {
      for (const subItem of item.subItems) {
        const { value, count, lowStock } = calculateItemStats(subItem);
        itemValue += value;
        itemCount += count;
        itemLowStockCount += lowStock;
      }
    }

    return { value: itemValue, count: itemCount, lowStock: itemLowStockCount };
  };

  for (const category of products) {
    for (const item of category.items) {
      const { value, count, lowStock } = calculateItemStats(item);
      totalValue += value;
      totalCount += count;
      lowStockCount += lowStock;
    }
  }

  return { totalValue, totalCount, lowStockCount };
};

const { totalValue, totalCount,lowStockCount } = calculateInventoryStats(products);
console.log("Total Inventory Value:", totalValue);
console.log("Total Product Count:", totalCount);
console.log("Low Stock Items:", lowStockCount);
  
  console.log("total value",totalValue);
 const totalProducts=totalCount;
  const inventoryValue=totalValue;

  const lowStockItems = lowStockCount;


  const activeVendors = 18;
  const formatNumber = (num) =>
  new Intl.NumberFormat('en-IN').format(num);

  const formatCurrency = (num) =>
    new Intl.NumberFormat('en-IN', {
      style: 'currency',
      currency: 'INR',
      maximumFractionDigits: 2,
    }).format(num);
  return (
    <div className="wrapper bg-white min-vh-100">
      <div className="content py-3 px-0">
        <div className="container">
          <div className="card w-100" style={{ boxShadow: '0 0.5rem 1rem rgba(0, 0, 0, 0.15)' }}>
            <div
              className="card-header"
              style={{
                background: 'linear-gradient(120deg, #3a7bd5, #00d2ff)',
                color: 'white',
                borderRadius: '10px 10px 0 0',
                padding: '12px 20px',
              }}
            >
              <h3 className="card-title mb-0 d-flex align-items-center" style={{ gap: '0.5rem' }}>
                <i className="bi bi-box-seam"></i>Inventory Grouping
              </h3>
              <p className="text-light mb-0" style={{ fontSize: '0.9rem' }}>
                Manage Client related Inventory
              </p>
            </div>

            <div className="card-body">
              <div className="row mb-4">
                { [
                  {
                    label: 'Total Products',
                    value: formatNumber(totalProducts),
                    icon: 'bi-boxes',
                    color: '#3a7bd5',
                  },
                  {
                    label: 'Inventory Value',
                    value: formatCurrency(inventoryValue),
                    icon: 'bi-currency-rupee',
                    color: '#28a745',
                  },
                  {
                    label: 'Low Stock Items',
                    value: formatNumber(lowStockItems),
                    icon: 'bi-exclamation-triangle',
                    color: '#ffc107',
                  },
                  {
                    label: 'Active Vendors',
                    value: formatNumber(activeVendors),
                    icon: 'bi-people',
                    color: '#17a2b8',
                  },
                ].map((stat, index) => (
                  <div className="col-md-3" key={index}>
                    <div
                      style={{
                        backgroundColor: 'white',
                        borderRadius: '10px',
                        boxShadow: '0 4px 12px rgba(0,0,0,0.5)',
                        padding: '16px',
                        marginBottom: '20px',
                        borderLeft: `5px solid ${stat.color}`,
                      }}
                    >
                      <div className="d-flex justify-content-between align-items-center">
                        <div>
                          <div
                            style={{
                              fontSize: '1.8rem',
                              fontWeight: 600,
                              lineHeight: 1.2,
                            }}
                          >
                            {stat.value}
                          </div>
                          <div style={{ color: '#6c757d', fontSize: '0.9rem' }}>
                            {stat.label}
                          </div>
                        </div>
                        <i
                          className={`bi ${stat.icon}`}
                          style={{ fontSize: '1.8rem', color: stat.color }}
                        ></i>
                      </div>
                    </div>
                  </div>
                ))}
              </div>
{/* 
              <div className="d-flex flex-wrap align-items-start gap-2 mb-4">
                <div className="position-relative" style={{ maxWidth: '300px' }}>
                  <i
                    className="bi bi-search"
                    style={{
                      position: 'absolute',
                      left: '12px',
                      top: '50%',
                      transform: 'translateY(-50%)',
                      color: '#6c757d',
                    }}
                  ></i>
                  <input
                    type="text"
                    className="form-control"
                    placeholder="Search inventory..."
                    style={{ paddingLeft: '40px' }}
                  />
                </div>
                <button
                  className="btn btn-primary d-flex align-items-center"
                  style={{ gap: '0.5rem' }}
                  //onClick={openModal}
                >
                  <i className="bi bi-plus-circle"></i>Add Product
                </button>
              </div> */}

              <Main products={products}/>
              <AddProductModal show={isModalOpen} onClose={closeModal} onProductAdded={handleProductAdded} />
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
