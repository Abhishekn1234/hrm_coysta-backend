import React, { useState, useEffect } from 'react';
import 'bootstrap/dist/css/bootstrap.min.css';
import '@fortawesome/fontawesome-free/css/all.min.css';
import Main from './Main';



// ✅ FULL MODAL WITH LEAD FORM FIELDS
function AddProductModal({ show, onClose }) {
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    phone: '',
    company: '',
    position: '',
    address: '',
    notes: '',
  });

  useEffect(() => {
    if (show) {
      setFormData({
        name: '',
        email: '',
        phone: '',
        company: '',
        position: '',
        address: '',
        notes: '',
      });
    }
  }, [show]);

  const handleChange = (e) => {
    const { name, value } = e.target;
    setFormData((prev) => ({ ...prev, [name]: value }));
  };

  const handleSubmit = (e) => {
    e.preventDefault();
    console.log('Product added (as lead):', formData);
    onClose();
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
                <h5 className="modal-title">Add New Product (as Lead)</h5>
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
                    <label className="form-label">Email</label>
                    <input
                      type="email"
                      className="form-control form-control-sm"
                      name="email"
                      value={formData.email}
                      onChange={handleChange}
                    />
                  </div>
                  <div className="col-md-6">
                    <label className="form-label">Phone</label>
                    <input
                      type="text"
                      className="form-control form-control-sm"
                      name="phone"
                      value={formData.phone}
                      onChange={handleChange}
                    />
                  </div>
                  <div className="col-md-6">
                    <label className="form-label">Company</label>
                    <input
                      type="text"
                      className="form-control form-control-sm"
                      name="company"
                      value={formData.company}
                      onChange={handleChange}
                    />
                  </div>
                  <div className="col-md-6">
                    <label className="form-label">Position</label>
                    <input
                      type="text"
                      className="form-control form-control-sm"
                      name="position"
                      value={formData.position}
                      onChange={handleChange}
                    />
                  </div>
                  <div className="col-md-12">
                    <label className="form-label">Address</label>
                    <textarea
                      className="form-control form-control-sm"
                      name="address"
                      value={formData.address}
                      onChange={handleChange}
                    ></textarea>
                  </div>
                  <div className="col-md-12">
                    <label className="form-label">Notes</label>
                    <textarea
                      className="form-control form-control-sm"
                      name="notes"
                      value={formData.notes}
                      onChange={handleChange}
                    ></textarea>
                  </div>
                </div>
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

  const openModal = () => setIsModalOpen(true);
  const closeModal = () => setIsModalOpen(false);
  useEffect(() => {
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
                <i className="bi bi-box-seam"></i>Inventory Management
              </h3>
              <p className="text-light mb-0" style={{ fontSize: '0.9rem' }}>
                Manage products, stock levels and vendors
              </p>
            </div>

            <div className="card-body">
              {/* Stats Cards */}
              <div className="row mb-4">
                {[
                  {
                    label: 'Total Products',
                    value: '1,248',
                    icon: 'bi-boxes',
                    color: '#3a7bd5',
                  },
                  {
                    label: 'Inventory Value',
                    value: '₹1.24M',
                    icon: 'bi-currency-rupee',
                    color: '#28a745',
                  },
                  {
                    label: 'Low Stock Items',
                    value: '42',
                    icon: 'bi-exclamation-triangle',
                    color: '#ffc107',
                  },
                  {
                    label: 'Active Vendors',
                    value: '18',
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

              {/* Search & Add Button */}
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
                  onClick={openModal}
                >
                  <i className="bi bi-plus-circle"></i>Add Product
                </button>
              </div>

              {/* Main Module Content */}
              <Main />

              {/* Modal */}
              <AddProductModal show={isModalOpen} onClose={closeModal} />
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}
