import React from "react";
import { FontAwesomeIcon } from '@fortawesome/react-fontawesome';
import { 
  faExclamationCircle
} from '@fortawesome/free-solid-svg-icons';
const CriticalInventory = () => {
  return (
    <>
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
        `}
      </style>

      <div className="card">
        <div className="card-header">
          <span>Critical Inventory</span>
          <button className="btn btn-sm btn-danger action-btn">
            <FontAwesomeIcon icon={faExclamationCircle} /> Reorder
          </button>
        </div>
        <div className="card-body">
          <div className="table-responsive">
            <table className="table table-hover">
              <thead>
                <tr>
                  <th>Item</th>
                  <th>Category</th>
                  <th>Current</th>
                  <th>Required</th>
                  <th>Urgency</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Steel Beams (50cm)</td>
                  <td>Materials</td>
                  <td>42</td>
                  <td>120</td>
                  <td className="priority-high">High</td>
                </tr>
                <tr>
                  <td>Electrical Wiring</td>
                  <td>Electrical</td>
                  <td>120m</td>
                  <td>500m</td>
                  <td className="priority-high">High</td>
                </tr>
                <tr>
                  <td>Concrete Mix</td>
                  <td>Materials</td>
                  <td>8 tons</td>
                  <td>20 tons</td>
                  <td className="priority-medium">Medium</td>
                </tr>
                <tr>
                  <td>Safety Helmets</td>
                  <td>PPE</td>
                  <td>15</td>
                  <td>50</td>
                  <td className="priority-medium">Medium</td>
                </tr>
                <tr>
                  <td>Industrial Pumps</td>
                  <td>Equipment</td>
                  <td>2</td>
                  <td>8</td>
                  <td className="priority-low">Low</td>
                </tr>
                <tr>
                  <td>PVC Pipes</td>
                  <td>Plumbing</td>
                  <td>85</td>
                  <td>50</td>
                  <td className="priority-low">OK</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </>
  );
};

export default CriticalInventory;
