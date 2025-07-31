import React, { useEffect } from 'react';
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { faUserPlus, faTimes } from '@fortawesome/free-solid-svg-icons';
const AddVendorModal = ({ show, onClose }) => {
  useEffect(() => {
    if (show) {
      document.body.style.overflow = 'hidden';
    } else {
      document.body.style.overflow = 'auto';
    }

    return () => {
      document.body.style.overflow = 'auto';
    };
  }, [show]);

  if (!show) {
    return null;
  }

  return (
    <div className="modal-overlay">
      <style jsx>{`
         .modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    right: 0;
    bottom: 0;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: flex-start;
    z-index: 1000;
    padding: 30px 15px;
    overflow-y: auto;
    opacity: 0;
    animation: fadeIn 0.3s ease-out forwards;
  }
  
  .modal-content {
    background: white;
    border-radius: 10px;
    width: 90%;
    max-width: 500px;
    margin: 0 auto;
    box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    position: relative;
    transform: translateY(-20px);
    animation: slideDown 0.3s ease-out forwards;
  }
  
  @keyframes fadeIn {
    from { opacity: 0; }
    to { opacity: 1; }
  }
  
  @keyframes slideDown {
    from { transform: translateY(-20px); }
    to { transform: translateY(0); }
  }
        .modal-header {
          background: linear-gradient(120deg, var(--primary), var(--secondary));
          color: white;
          padding: 14px 18px;
          display: flex;
          justify-content: space-between;
          align-items: center;
          border-radius: 10px 10px 0 0;
        }
        
        .modal-title {
          margin: 0;
          font-size: 1.3rem;
          display: flex;
          align-items: center;
        }
        
        .modal-body {
          padding: 18px;
        }
        
        .modal-footer {
          padding: 14px 18px;
          background: #f8f9fa;
          border-top: 1px solid #eee;
          display: flex;
          justify-content: flex-end;
          gap: 10px;
          border-radius: 0 0 10px 10px;
        }
        
        .btn {
          padding: 7px 14px;
          border-radius: 5px;
          border: none;
          cursor: pointer;
          transition: all 0.2s;
        }
        
        .btn-secondary {
          background-color: #6c757d;
          color: white;
        }
        
        .btn-secondary:hover {
          background-color: #5a6268;
        }
        
        .btn-primary {
          color: white;
        }
        
        .btn-primary:hover {
          background-color: var(--secondary);
        }
        
        .btn-close {
          background: none;
          border: none;
          color: white;
          font-size: 1.1rem;
          cursor: pointer;
          opacity: 0.8;
          padding: 4px;
        }

        .btn-close:hover {
          opacity: 1;
        }

        .form-label {
          display: block;
          margin-bottom: 7px;
          font-weight: 500;
          font-size: 1rem;
        }
        
        .form-control, .form-select {
          width: 100%;
          padding: 9px;
          border: 1px solid #ddd;
          border-radius: 5px;
          font-size: 0.95rem;
        }
        
        .form-control:focus, .form-select:focus {
          outline: none;
          border-color: var(--primary);
          box-shadow: 0 0 0 3px rgba(60, 141, 188, 0.2);
        }
        
        textarea.form-control {
          min-height: 70px;
        }
        
        .mb-3 {
          margin-bottom: 14px;
        }
        
        .row {
          display: flex;
          flex-wrap: wrap;
          margin: 0 -9px;
        }
        
        .col-md-6 {
          flex: 0 0 50%;
          max-width: 50%;
          padding: 0 9px;
          box-sizing: border-box;
        }

        @media (max-width: 768px) {
          .modal-content {
            width: 95%;
            max-width: 420px;
          }
        }
      `}</style>

      <div className="modal-content">
        <div className="modal-header">
          <h5 className="modal-title">
  <FontAwesomeIcon icon={faUserPlus} className="me-2" />
  Add New Vendor
</h5>
               <button
           type="button"
           className="btn-close"
           onClick={onClose}
           aria-label="Close"
         >
           <FontAwesomeIcon icon={faTimes} />
         </button>
        </div>
        <div className="modal-body">
          <form>
            <div className="mb-3">
              <label className="form-label">Vendor Name</label>
              <input type="text" className="form-control" placeholder="Enter vendor name" />
            </div>
            
            <div className="row mb-3">
              <div className="col-md-6">
                <label className="form-label">Contact Person</label>
                <input type="text" className="form-control" placeholder="Name" />
              </div>
              <div className="col-md-6">
                <label className="form-label">Phone</label>
                <input type="text" className="form-control" placeholder="Phone number" />
              </div>
            </div>
            
            <div className="mb-3">
              <label className="form-label">Email</label>
              <input type="email" className="form-control" placeholder="Email address" />
            </div>
            
            <div className="mb-3">
              <label className="form-label">Address</label>
              <textarea className="form-control" rows="2" placeholder="Full address"></textarea>
            </div>
            
            <div className="mb-3">
              <label className="form-label">Category</label>
              <select className="form-select">
                <option>Materials</option>
                <option>Electrical</option>
                <option>Mechanical</option>
                <option>PPE</option>
                <option>Tools</option>
                <option>General</option>
              </select>
            </div>
            
            <div className="mb-3">
              <label className="form-label">Payment Terms</label>
              <input type="text" className="form-control" placeholder="Net 30, etc." />
            </div>
          </form>
        </div>
        <div className="modal-footer">
          <button type="button" className="btn btn-secondary" onClick={onClose}>
            Cancel
          </button>
          <button type="button" className="btn btn-primary">
            Save Vendor
          </button>
        </div>
      </div>
    </div>
  );
};

export default AddVendorModal;