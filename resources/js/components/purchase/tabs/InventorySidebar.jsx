import { useState } from 'react';
import React from 'react';  // Add this line at the very top
const InventorySidebar = () => {
  return (
    <div className="col-lg-4">
      <div className="card"  style={{ boxShadow: '0 0.5rem 1rem rgba(0, 0, 0, 0.15)' }}>
        <div className="card-header" style={{
              background: 'linear-gradient(120deg, #3a7bd5, #00d2ff)',
              color: 'white',
              borderRadius: '10px 10px 0 0',
              padding: '12px 20px'
            }}>
          <h5 className="mb-0">Inventory Summary</h5>
        </div>
        <div className="card-body">
          <div className="mb-4">
            <h6 className="section-title">Categories</h6>
            <div className="d-flex flex-wrap">
              <span className="badge bg-primary me-2 mb-2">Electronics (420)</span>
              <span className="badge bg-success me-2 mb-2">Furniture (315)</span>
              <span className="badge bg-info me-2 mb-2">Machinery (280)</span>
              <span className="badge bg-warning me-2 mb-2">Office Supplies (233)</span>
            </div>
          </div>
          
          <div className="mb-4">
            <h6 className="section-title">Stock Status</h6>
            <div className="d-flex mb-2">
              <div className="me-3" style={{ width: '60%' }}>In Stock</div>
              <div className="progress flex-grow-1" style={{ height: '20px' }}>
                <div className="progress-bar bg-success" role="progressbar" style={{ width: '85%' }}>85%</div>
              </div>
            </div>
            <div className="d-flex mb-2">
              <div className="me-3" style={{ width: '60%' }}>Low Stock</div>
              <div className="progress flex-grow-1" style={{ height: '20px' }}>
                <div className="progress-bar bg-warning" role="progressbar" style={{ width: '12%' }}>12%</div>
              </div>
            </div>
            <div className="d-flex">
              <div className="me-3" style={{ width: '60%' }}>Out of Stock</div>
              <div className="progress flex-grow-1" style={{ height: '20px' }}>
                <div className="progress-bar bg-danger" role="progressbar" style={{ width: '3%' }}>3%</div>
              </div>
            </div>
          </div>
          
          <div>
            <h6 className="section-title">Recent Activity</h6>
            <div className="list-group">
              <div className="list-group-item border-0 px-0 py-2">
                <div className="d-flex">
                  <div className="me-3">
                    <i className="bi bi-plus-circle-fill text-success"></i>
                  </div>
                  <div>
                    <div>Added "Wireless Mouse"</div>
                    <small className="text-muted">Today, 10:30 AM</small>
                  </div>
                </div>
              </div>
              <div className="list-group-item border-0 px-0 py-2">
                <div className="d-flex">
                  <div className="me-3">
                    <i className="bi bi-arrow-down-up text-primary"></i>
                  </div>
                  <div>
                    <div>Updated stock for "Office Desk"</div>
                    <small className="text-muted">Yesterday, 4:15 PM</small>
                  </div>
                </div>
              </div>
              <div className="list-group-item border-0 px-0 py-2">
                <div className="d-flex">
                  <div className="me-3">
                    <i className="bi bi-truck text-info"></i>
                  </div>
                  <div>
                    <div>New shipment received</div>
                    <small className="text-muted">Yesterday, 11:20 AM</small>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default InventorySidebar;