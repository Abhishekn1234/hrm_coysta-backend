import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faFileInvoice , faTimes} from "@fortawesome/free-solid-svg-icons";
import { useEffect } from "react";

const CreateRequisitionModal = ({ show, onClose }) => {
  useEffect(() => {
    if (show) {
      document.body.style.overflow = "hidden";
    } else {
      document.body.style.overflow = "auto";
    }

    return () => {
      document.body.style.overflow = "auto";
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
          padding: 40px 20px;
          overflow-y: auto;
        }

        .modal-content {
          background: white;
          border-radius: 12px;
          width: 90%;
          max-width: 800px;
          margin: 0 0;
          box-shadow: 0 5px 15px rgba(0, 0, 0, 0.2);
          position: relative;
          animation: modalFadeIn 0.3s;
        }

        @keyframes modalFadeIn {
          from {
            opacity: 0;
            transform: translateY(-20px);
          }
          to {
            opacity: 1;
            transform: translateY(0);
          }
        }

        .modal-header {
          background: linear-gradient(120deg, var(--primary), var(--secondary));
          color: white;
          padding: 15px 20px;
          display: flex;
          justify-content: space-between;
          align-items: center;
          border-radius: 12px 12px 0 0;
        }

        .modal-title {
          margin: 0;
          font-size: 1.25rem;
          display: flex;
          align-items: center;
        }

        .modal-body {
          padding: 20px;
        }

        .modal-footer {
          padding: 15px 20px;
          background: #f8f9fa;
          border-top: 1px solid #eee;
          display: flex;
          justify-content: flex-end;
          gap: 10px;
          border-radius: 0 0 12px 12px;
        }

        .btn {
          padding: 8px 16px;
          border-radius: 6px;
          border: none;
          cursor: pointer;
          font-size: 0.9rem;
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
          background-color: var(--primary);
          color: white;
        }

        .btn-primary:hover {
          background-color: var(--secondary);
        }

        .btn-close {
          background: none;
          border: none;
          color: white;
          font-size: 1.2rem;
          cursor: pointer;
          opacity: 0.8;
          padding: 5px;
        }

        .btn-close:hover {
          opacity: 1;
        }

        .form-label {
          display: block;
          margin-bottom: 8px;
          font-weight: 500;
        }

        .form-control,
        .form-select {
          width: 100%;
          padding: 10px;
          border: 1px solid #ddd;
          border-radius: 6px;
          font-size: 0.9rem;
        }

        .form-control:focus,
        .form-select:focus {
          outline: none;
          border-color: var(--primary);
          box-shadow: 0 0 0 3px rgba(60, 141, 188, 0.2);
        }

        textarea.form-control {
          min-height: 60px;
          resize: vertical;
        }

        .mb-3 {
          margin-bottom: 16px;
        }

        .row {
          display: flex;
          flex-wrap: wrap;
          margin: 0 -10px;
        }

        .col-md-4,
        .col-md-6 {
          flex: 0 0 100%;
          max-width: 100%;
          padding: 0 10px;
          box-sizing: border-box;
        }

        @media (min-width: 768px) {
          .col-md-4 {
            flex: 0 0 33.333333%;
            max-width: 33.333333%;
          }
          .col-md-6 {
            flex: 0 0 50%;
            max-width: 50%;
          }
        }

        @media (max-width: 768px) {
          .modal-overlay {
            padding: 20px 10px;
          }

          .modal-content {
            width: 95%;
          }
        }
      `}</style>

      <div className="modal-content">
        <div className="modal-header">
          <h5 className="modal-title">
            <FontAwesomeIcon icon={faFileInvoice} className="me-2" />
            Create Purchase Requisition
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
            <div className="row mb-3">
              <div className="col-md-6">
                <label className="form-label">Department</label>
                <select className="form-select">
                  <option>Construction</option>
                  <option>Electrical</option>
                  <option>Mechanical</option>
                  <option>Safety</option>
                  <option>Administration</option>
                </select>
              </div>
              <div className="col-md-6">
                <label className="form-label">Priority</label>
                <select className="form-select">
                  <option>Low</option>
                  <option>Medium</option>
                  <option>High</option>
                  <option>Critical</option>
                </select>
              </div>
            </div>

            <div className="mb-3">
              <label className="form-label">Item Description</label>
              <textarea
                className="form-control"
                rows="2"
                placeholder="Describe the item or materials needed"
              ></textarea>
            </div>

            <div className="row mb-3">
              <div className="col-md-4">
                <label className="form-label">Quantity</label>
                <input
                  type="number"
                  className="form-control"
                  min="1"
                  value="1"
                />
              </div>
              <div className="col-md-4">
                <label className="form-label">Unit</label>
                <select className="form-select">
                  <option>Units</option>
                  <option>Pieces</option>
                  <option>Meters</option>
                  <option>Liters</option>
                  <option>Kg</option>
                  <option>Tons</option>
                </select>
              </div>
              <div className="col-md-4">
                <label className="form-label">Needed By</label>
                <input type="date" className="form-control" />
              </div>
            </div>

            <div className="mb-3">
              <label className="form-label">Justification</label>
              <textarea
                className="form-control"
                rows="2"
                placeholder="Explain why this item is needed"
              ></textarea>
            </div>

            <div className="mb-3">
              <label className="form-label">Preferred Vendor (if any)</label>
              <input
                type="text"
                className="form-control"
                placeholder="Vendor name"
              />
            </div>
          </form>
        </div>
        <div className="modal-footer">
          <button type="button" className="btn btn-secondary" onClick={onClose}>
            Cancel
          </button>
          <button type="button" className="btn btn-primary">
            Submit Requisition
          </button>
        </div>
      </div>
    </div>
  );
};

export default CreateRequisitionModal;
