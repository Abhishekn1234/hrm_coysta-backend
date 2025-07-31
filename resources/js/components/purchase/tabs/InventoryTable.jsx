  
import { useState } from 'react';
import React from 'react';  // Add this line at the very top

const InventoryTable = ({ 
  data, 
  expandedCategories, 
  expandedItems, 
  toggleCategory, 
  toggleItem 
}) => {
  return (
    <div className="table-responsive">
      <table className="table tree-table table-hover mb-0">
        <thead className="table-light">
          <tr>
            <th>Name *</th>
            <th>SKU</th>
            <th>Category *</th>
            <th>Stock</th>
            <th>Unit *</th>
            <th>Worth (₹)</th>
            <th>Status</th>
            <th>Actions</th>
          </tr>
        </thead>
        <tbody id="inventoryBody">
          {data.categories.map(category => (
            <React.Fragment key={category.name}>
              <tr className="category-row">
                <td colSpan="8" className="bg-light fw-bold">
                  <span 
                    className="toggle-icon" 
                    onClick={() => toggleCategory(category.name)}
                    style={{ cursor: 'pointer' }}
                  >
                    <i className={`bi bi-chevron-${expandedCategories.includes(category.name) ? 'down' : 'right'}`}></i>
                  </span>
                  {category.name}
                </td>
              </tr>
              
              {expandedCategories.includes(category.name) && category.items.map(item => (
                <React.Fragment key={item.id}>
                  <tr className="main-item">
                    <td>
                      <span 
                        className="toggle-icon" 
                        onClick={() => toggleItem(item.id)}
                        style={{ cursor: 'pointer' }}
                      >
                        {item.subItems.length > 0 ? 
                          (expandedItems.includes(item.id) ? '▼' : '▶') : ''}
                      </span>
                      <input 
                        type="text" 
                        className="form-control form-control-sm" 
                        value={item.name} 
                        onChange={(e) => {/* Handle name change */}} 
                        required 
                      />
                    </td>
                    <td>
                      <input 
                        type="text" 
                        className="form-control form-control-sm" 
                        value={item.sku} 
                        onChange={(e) => {/* Handle SKU change */}} 
                      />
                    </td>
                    <td>
                      <select 
                        className="form-select form-select-sm" 
                        value={item.category} 
                        onChange={(e) => {/* Handle category change */}} 
                        required
                      >
                        <option>Electronics</option>
                        <option>Furniture</option>
                        <option>Machinery</option>
                      </select>
                    </td>
                    <td>
                      <input 
                        type="number" 
                        className="form-control form-control-sm" 
                        value={item.stock} 
                        onChange={(e) => {/* Handle stock change */}} 
                        min="0" 
                      />
                      <small className={`d-block ${item.stock < item.minStock ? 'text-danger fw-bold' : 'text-muted'}`}>
                        {item.stock < item.minStock ? 'Low stock!' : `Min: ${item.minStock}`}
                      </small>
                    </td>
                    <td>
                      <select 
                        className="form-select form-select-sm" 
                        value={item.unit} 
                        onChange={(e) => {/* Handle unit change */}} 
                        required
                      >
                        <option>Piece</option>
                        <option>Set</option>
                        <option>Kg</option>
                        <option>Liter</option>
                      </select>
                    </td>
                    <td>
                      <input 
                        type="number" 
                        className="form-control form-control-sm" 
                        value={item.worth} 
                        onChange={(e) => {/* Handle worth change */}} 
                      />
                    </td>
                    <td>
                      <span className={`status-badge ${item.status === 'Active' ? 'status-active' : 'status-inactive'}`}>
                        {item.status}
                      </span>
                    </td>
                    <td>
                      <button className="btn btn-sm btn-info action-btn" data-bs-toggle="modal" data-bs-target="#detailsModal">
                        <i className="bi bi-info-circle"></i>
                      </button>
                      {item.subItems.length > 0 && (
                        <button className="btn btn-sm btn-success action-btn add-sub">
                          <i className="bi bi-plus-lg"></i>
                        </button>
                      )}
                      <button className="btn btn-sm btn-danger action-btn delete">
                        <i className="bi bi-trash"></i>
                      </button>
                    </td>
                  </tr>
                  
                  {expandedItems.includes(item.id) && item.subItems.map(subItem => (
                    <tr className="level-1" key={subItem.id}>
                      <td>
                        <input 
                          type="text" 
                          className="form-control form-control-sm" 
                          value={subItem.name} 
                          onChange={(e) => {/* Handle name change */}} 
                          required 
                        />
                      </td>
                      <td>
                        <input 
                          type="text" 
                          className="form-control form-control-sm" 
                          value={subItem.sku} 
                          onChange={(e) => {/* Handle SKU change */}} 
                        />
                      </td>
                      <td>
                        <select 
                          className="form-select form-select-sm" 
                          value={subItem.category} 
                          onChange={(e) => {/* Handle category change */}} 
                        >
                          <option>Electronics</option>
                          <option>Furniture</option>
                          <option>Machinery</option>
                        </select>
                      </td>
                      <td>
                        <input 
                          type="number" 
                          className="form-control form-control-sm" 
                          value={subItem.stock} 
                          onChange={(e) => {/* Handle stock change */}} 
                          min="0" 
                        />
                        <small className={`d-block ${subItem.stock < subItem.minStock ? 'text-danger fw-bold' : 'text-muted'}`}>
                          {subItem.stock < subItem.minStock ? 'Low stock!' : `Min: ${subItem.minStock}`}
                        </small>
                      </td>
                      <td>
                        <select 
                          className="form-select form-select-sm" 
                          value={subItem.unit} 
                          onChange={(e) => {/* Handle unit change */}} 
                        >
                          <option>Piece</option>
                          <option>Set</option>
                        </select>
                      </td>
                      <td>
                        <input 
                          type="number" 
                          className="form-control form-control-sm" 
                          value={subItem.worth} 
                          onChange={(e) => {/* Handle worth change */}} 
                        />
                      </td>
                      <td>
                        <span className={`status-badge ${subItem.status === 'Active' ? 'status-active' : 'status-inactive'}`}>
                          {subItem.status}
                        </span>
                      </td>
                      <td>
                        <button className="btn btn-sm btn-info action-btn" data-bs-toggle="modal" data-bs-target="#detailsModal">
                          <i className="bi bi-info-circle"></i>
                        </button>
                        <button className="btn btn-sm btn-danger action-btn delete">
                          <i className="bi bi-trash"></i>
                        </button>
                      </td>
                    </tr>
                  ))}
                </React.Fragment>
              ))}
            </React.Fragment>
          ))}
        </tbody>
      </table>
    </div>
  );
};

export default InventoryTable;