import React from 'react';  // Add this line at the very top
import { useState } from 'react';
import InventoryTable from './tabs/InventoryTable';
import InventorySidebar from './tabs/InventorySidebar';

const Main = () => {
  const [inventoryData, setInventoryData] = useState({
    categories: [
      {
        name: 'Electronics',
        items: [
          {
            id: 1,
            name: 'Laptop',
            sku: 'LP-1001',
            category: 'Electronics',
            stock: 120,
            minStock: 20,
            unit: 'Piece',
            worth: 1250000,
            status: 'Active',
            subItems: [
              {
                id: 2,
                name: 'Laptop Charger',
                sku: 'LP-CHG-01',
                category: 'Electronics',
                stock: 150,
                minStock: 30,
                unit: 'Piece',
                worth: 225000,
                status: 'Active'
              }
            ]
          }
        ]
      },
      {
        name: 'Furniture',
        items: [
          {
            id: 3,
            name: 'Office Desk',
            sku: 'DSK-2001',
            category: 'Furniture',
            stock: 45,
            minStock: 10,
            unit: 'Piece',
            worth: 675000,
            status: 'Active',
            subItems: []
          },
          {
            id: 4,
            name: 'Old Model Printer',
            sku: 'PRT-3001',
            category: 'Electronics',
            stock: 5,
            minStock: 10,
            unit: 'Piece',
            worth: 75000,
            status: 'Inactive',
            subItems: []
          }
        ]
      }
    ]
  });

  const [expandedCategories, setExpandedCategories] = useState(['Electronics', 'Furniture']);
  const [expandedItems, setExpandedItems] = useState([1, 3]);

  const toggleCategory = (categoryName) => {
    if (expandedCategories.includes(categoryName)) {
      setExpandedCategories(expandedCategories.filter(c => c !== categoryName));
    } else {
      setExpandedCategories([...expandedCategories, categoryName]);
    }
  };

  const toggleItem = (itemId) => {
    if (expandedItems.includes(itemId)) {
      setExpandedItems(expandedItems.filter(id => id !== itemId));
    } else {
      setExpandedItems([...expandedItems, itemId]);
    }
  };

  const handleSave = () => {
    // Save logic here
    console.log('Inventory saved', inventoryData);
  };

  return (
    <div className="row">
      <div className="col-lg-8">
        <div className="card"  style={{ boxShadow: '0 0.5rem 1rem rgba(0, 0, 0, 0.2)' }}>
          <div className="card-header d-flex justify-content-between align-items-center " style={{
              background: 'linear-gradient(120deg, #3a7bd5, #00d2ff)',
              color: 'white',
              borderRadius: '10px 10px 0 0',
              padding: '12px 20px'
            }}>
            <h5 className="mb-0">Product Inventory</h5>
            <div className="d-flex align-items-center">
              <button 
                className="btn btn-sm btn-light" 
                style={{ marginRight: '8px' }}  // Manual spacing
              >
                <i className="bi bi-filter"></i> Filters
              </button>
              <button className="btn btn-sm btn-light">
                <i className="bi bi-download"></i> Export
              </button>
            </div>
          </div>
          <div className="card-body">
            <InventoryTable 
              data={inventoryData}
              expandedCategories={expandedCategories}
              expandedItems={expandedItems}
              toggleCategory={toggleCategory}
              toggleItem={toggleItem}
            />
            
            <div className="d-flex justify-content-between align-items-center mt-3">
              <div className="text-muted">Showing 5 of 1,248 items</div>
              <nav>
                <ul className="pagination pagination-sm mb-0">
                  <li className="page-item disabled"><a className="page-link" href="#">Previous</a></li>
                  <li className="page-item active"><a className="page-link" href="#">1</a></li>
                  <li className="page-item"><a className="page-link" href="#">2</a></li>
                  <li className="page-item"><a className="page-link" href="#">3</a></li>
                  <li className="page-item"><a className="page-link" href="#">Next</a></li>
                </ul>
              </nav>
            </div>
          </div>
        </div>
        
        <div className="text-end mt-4">
          <button className="btn btn-primary me-2" onClick={handleSave}>
            <i className="bi bi-save me-2"></i>Save Inventory
          </button>
          <button className="btn btn-outline-secondary">
            Cancel
          </button>
        </div>
      </div>
      
      <InventorySidebar />
    </div>
  );
};

export default Main;