import React, { useState } from "react";
import { FontAwesomeIcon } from "@fortawesome/react-fontawesome";
import { faPlus } from "@fortawesome/free-solid-svg-icons";
import AddVendorModal from "../../Modal/AddVendorModal";

const VendorsTab = () => {
  const [showVendorModal, setShowVendorModal] = useState(false);

  return (
    <div className="card">
      <style>
        {`

         .card {
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0,0,0,0.06);
            margin-bottom: 25px;
            border: none;
          }
          
          .card-header {
            background: white;
            border-bottom: 1px solid #eee;
            padding: 18px 25px;
            font-weight: 600;
            font-size: 1.2rem;
            color: #333;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-radius: 10px 10px 0 0 !important;
          }
          
          .action-btn {
            padding: 6px 15px;
            border-radius: 6px;
            font-size: 0.9rem;
            display: flex;
            align-items: center;
            gap: 6px;
          }
          
          .table-responsive {
            border-radius: 8px;
            overflow: hidden;
          }
          
          .table th {
            background-color: #f8f9fa;
            color: #444;
            font-weight: 600;
            padding: 14px 18px;
          }
          
          .table td {
            padding: 14px 18px;
            vertical-align: middle;
          }
          .text-warning {
            color: #f39c12;
          }
          
          .ms-2 {
            margin-left: 0.5rem;
          }
          
          .far.fa-star {
            font-weight: 400;
          }
          
          .fas.fa-star, .fas.fa-star-half-alt {
            font-weight: 900;
          }
        `}
      </style>

      <div className="card-header">
        <span>Vendor Management</span>
        <button
          onClick={() => setShowVendorModal(true)}
          className="btn btn-sm btn-primary action-btn"
          data-bs-toggle="modal"
          data-bs-target="#addVendorModal"
        >
          <FontAwesomeIcon icon={faPlus} /> Add
        </button>
      </div>
      <div className="card-body">
        <div className="table-responsive">
          <table className="table table-hover">
            <thead>
              <tr>
                <th>Vendor</th>
                <th>Category</th>
                <th>Contact</th>
                <th>Rating</th>
                <th>Action</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td>SteelCo Inc.</td>
                <td>Materials</td>
                <td>
                  John Smith
                  <br />
                  john@steelco.com
                </td>
                <td>
                  <i className="fas fa-star text-warning"></i>
                  <i className="fas fa-star text-warning"></i>
                  <i className="fas fa-star text-warning"></i>
                  <i className="fas fa-star text-warning"></i>
                  <i className="fas fa-star-half-alt text-warning"></i>
                  <span className="ms-2">4.5</span>
                </td>
                <td>
                  <button className="btn btn-sm btn-outline-primary">
                    View
                  </button>
                </td>
              </tr>
              <tr>
                <td>Electro Supplies</td>
                <td>Electrical</td>
                <td>
                  Sarah Johnson
                  <br />
                  sarah@electro.com
                </td>
                <td>
                  <i className="fas fa-star text-warning"></i>
                  <i className="fas fa-star text-warning"></i>
                  <i className="fas fa-star text-warning"></i>
                  <i className="fas fa-star text-warning"></i>
                  <i className="far fa-star text-warning"></i>
                  <span className="ms-2">4.0</span>
                </td>
                <td>
                  <button className="btn btn-sm btn-outline-primary">
                    View
                  </button>
                </td>
              </tr>
              <tr>
                <td>Builders Depot</td>
                <td>General</td>
                <td>
                  Mike Thompson
                  <br />
                  mike@builders.com
                </td>
                <td>
                  <i className="fas fa-star text-warning"></i>
                  <i className="fas fa-star text-warning"></i>
                  <i className="fas fa-star text-warning"></i>
                  <i className="far fa-star text-warning"></i>
                  <i className="far fa-star text-warning"></i>
                  <span className="ms-2">3.0</span>
                </td>
                <td>
                  <button className="btn btn-sm btn-outline-primary">
                    View
                  </button>
                </td>
              </tr>
              <tr>
                <td>Safety Gear Ltd.</td>
                <td>PPE</td>
                <td>
                  Emily Wilson
                  <br />
                  emily@safetygear.com
                </td>
                <td>
                  <i className="fas fa-star text-warning"></i>
                  <i className="fas fa-star text-warning"></i>
                  <i className="fas fa-star text-warning"></i>
                  <i className="fas fa-star text-warning"></i>
                  <i className="fas fa-star text-warning"></i>
                  <span className="ms-2">5.0</span>
                </td>
                <td>
                  <button className="btn btn-sm btn-outline-primary">
                    View
                  </button>
                </td>
              </tr>
              <tr>
                <td>Concrete Masters</td>
                <td>Materials</td>
                <td>
                  Robert Davis
                  <br />
                  robert@concrete.com
                </td>
                <td>
                  <i className="fas fa-star text-warning"></i>
                  <i className="fas fa-star text-warning"></i>
                  <i className="fas fa-star text-warning"></i>
                  <i className="fas fa-star text-warning"></i>
                  <i className="far fa-star text-warning"></i>
                  <span className="ms-2">4.0</span>
                </td>
                <td>
                  <button className="btn btn-sm btn-outline-primary">
                    View
                  </button>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
      <AddVendorModal
        show={showVendorModal}
        onClose={() => setShowVendorModal(false)}
      />
    </div>
  );
};

export default VendorsTab;
