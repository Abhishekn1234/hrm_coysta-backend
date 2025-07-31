import { useState, useEffect, useRef } from 'react';
import React from 'react';
import bootstrap from 'bootstrap/dist/js/bootstrap.bundle.min.js';
import { Modal } from 'bootstrap';

const InventoryTable = ({
  data,
  expandedCategories,
  expandedItems,
  toggleCategory,
  toggleItem,
  onDataChange,
  dataforSearch
}) => {
  const [selectedItem, setSelectedItem] = useState(null);
  const [activeTab, setActiveTab] = useState('basic');
  const [searchTerm, setSearchTerm] = useState('');
  //console.log(searchTerm);
  const [inventoryData, setInventoryData] = useState(data);
  const [editingCategory, setEditingCategory] = useState(null);
  const [tempCategoryName, setTempCategoryName] = useState('');
  const modalRef = useRef(null);
  const bsModal = useRef(null);
  const lastFocusedElement = useRef(null);
  const [saveMessage, setSaveMessage] = useState('');
  const [messageType, setMessageType] = useState(''); // 'success' or 'error'
  
  

  const products = [
  { id: 1, item_name: "Apple iPhone" },
  { id: 2, item_name: "Samsung Galaxy" },
  { id: 3, item_name: "Google Pixel" }
];
dataforSearch=dataforSearch.products;

console.log("this is my data",dataforSearch);
 const [showDropdown, setShowDropdown] = useState(false);
const filteredProducts = (dataforSearch || []).filter(product =>
  product.item_name?.toLowerCase().includes(searchTerm.toLowerCase())
);


 useEffect(() => {
  if (filteredProducts.length > 0) {
    console.log('Matching products found:', filteredProducts);
  } else {
    console.log('No matching products found for:', searchTerm);
  }
}, [searchTerm, dataforSearch]);


//   const handleSaveAll = async () => {
//     console.log(inventoryData);
//   try {
//     // Example: send inventoryData to your backend API
//     // Adjust this fetch/axios call to your API endpoint & method

//     const response = await fetch('/api/v1/inventories/saveinventory', {
//       method: 'POST',
//       headers: { 'Content-Type': 'application/json' },
//       body: JSON.stringify(inventoryData),
//     });

//     // if (!response.ok) {
//     //   throw new Error('Data Save11 Error');
//     // }
//     console.log(response);
//     //const result = await response.json();
//     onDataChange(response);
//     alert('All changes saved successfully!');
//   } catch (error) {
//     alert('Data save Error');
//   }
// };
    const handleSaveAll = async () => {
      setSaveMessage(''); // Clear previous message

      try {
        const response = await fetch('/api/v1/inventories/saveinventory', {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(inventoryData),
        });

        if (!response.ok) {
          throw new Error('Failed to save inventory');
        }

        const result = await response.json();
        onDataChange(result);

        setMessageType('success');
        setSaveMessage('All changes saved successfully!');
        setTimeout(() => {
          setSaveMessage('');
          setMessageType('');
        }, 3000);
      } catch (error) {
        console.error('Save Error:', error);
        setMessageType('error');
        setSaveMessage('Failed to save data. Please try again.');
        setTimeout(() => {
          setSaveMessage('');
          setMessageType('');
        }, 3000);
      }
    };

  useEffect(() => {
    if (modalRef.current) {
      bsModal.current = new Modal(modalRef.current, { focus: true });

      modalRef.current.addEventListener('hidden.bs.modal', () => {
        setSelectedItem(null);
        setActiveTab('basic');
        lastFocusedElement.current?.focus();
      });

      modalRef.current.addEventListener('shown.bs.modal', () => {
        const closeButton = modalRef.current.querySelector('.btn-close');
        if (closeButton) closeButton.focus();
      });
    }

    return () => {
      if (bsModal.current) {
        bsModal.current.dispose();
      }
    };
  }, []);

  useEffect(() => {
    if (selectedItem && bsModal.current) {
      lastFocusedElement.current = document.activeElement;
      bsModal.current.show();
      modalRef.current.removeAttribute('aria-hidden');
      modalRef.current.setAttribute('aria-modal', 'true');
    } else if (bsModal.current) {
      bsModal.current.hide();
    }
  }, [selectedItem]);

  const handleBackdropClick = (e) => {
    if (e.target === modalRef.current) {
      bsModal.current.hide();
    }
  };

  useEffect(() => {
    const handleTabKey = (e) => {
      if (!selectedItem || !modalRef.current) return;
      const focusableElements = modalRef.current.querySelectorAll(
        'button, [href], input, select, textarea, [tabindex]:not([tabindex="-1"])'
      );
      if (focusableElements.length === 0) return;

      const first = focusableElements[0];
      const last = focusableElements[focusableElements.length - 1];

      if (e.key === 'Tab') {
        if (e.shiftKey && document.activeElement === first) {
          e.preventDefault();
          last.focus();
        } else if (!e.shiftKey && document.activeElement === last) {
          e.preventDefault();
          first.focus();
        }
      }
    };

    if (selectedItem) {
      document.addEventListener('keydown', handleTabKey);
      return () => document.removeEventListener('keydown', handleTabKey);
    }
  }, [selectedItem]);

  const handleAddProduct = () => {
    const newCategoryIndex = inventoryData.categories.length + 1;
    const newCategoryName = `Product Group ${newCategoryIndex}`;

    const newMainProduct = {
      id: Date.now(),
      name: '',
      sku: '',
      category: newCategoryName,
      stock: 0,
      minStock: 5,
      unit: 'Piece',
      worth: 0,
      status: 'Active',
      subItems: []
    };

    const updatedData = {
      categories: [
        ...inventoryData.categories,
        {
          name: newCategoryName,
          items: [newMainProduct]
        }
      ]
    };

    setInventoryData(updatedData);
  };

  const handleAddSubItem = (categoryName, itemId) => {
    const updatedData = {
      categories: inventoryData.categories.map(category => {
        if (category.name !== categoryName) return category;
        return {
          ...category,
          items: category.items.map(item => {
            if (item.id !== itemId) return item;
            return {
              ...item,
              subItems: [
                ...item.subItems,
                {
                  id: Date.now(),
                  name: '',
                  sku: '',
                  category: item.category,
                  stock: 0,
                  minStock: 5,
                  unit: item.unit,
                  worth: 0,
                  status: 'Active',
                  subItems: []
                }
              ]
            };
          })
        };
      })
    };

    if (!expandedItems.includes(itemId)) {
      toggleItem(itemId); // auto-expand subitems on add
    }

    setInventoryData(updatedData);
  };

  const handleDeleteItem = (categoryName, itemId) => {
    let updatedCategories = inventoryData.categories.map(category => {
      if (category.name !== categoryName) return category;
      return {
        ...category,
        items: category.items.filter(item => item.id !== itemId)
      };
    });

    // Remove categories that have no items left
    updatedCategories = updatedCategories.filter(category => category.items.length > 0);

    setInventoryData({
      categories: updatedCategories
    });
  };

  const handleDeleteSubItem = (categoryName, parentItemId, subItemId) => {
    let updatedCategories = inventoryData.categories.map(category => {
      if (category.name !== categoryName) return category;
      return {
        ...category,
        items: category.items.map(item => {
          if (item.id !== parentItemId) return item;
          return {
            ...item,
            subItems: item.subItems.filter(subItem => subItem.id !== subItemId)
          };
        })
      };
    });

    updatedCategories = updatedCategories.filter(category => category.items.length > 0);

    setInventoryData({
      categories: updatedCategories
    });
  };

  const handleItemChange = (categoryName, itemId, field, value, isSubItem = false, subItemId = null) => {
    setInventoryData(prevData => {
      const updatedCategories = prevData.categories.map(category => {
        if (category.name !== categoryName) return category;
        
        return {
          ...category,
          items: category.items.map(item => {
            if (item.id !== itemId) return item;
            
            if (isSubItem) {
              return {
                ...item,
                subItems: item.subItems.map(subItem => {
                  if (subItem.id !== subItemId) return subItem;
                  return { ...subItem, [field]: value };
                })
              };
            } else {
              return { ...item, [field]: value };
            }
          })
        };
      });
      
      return { categories: updatedCategories };
    });
  };

  const startEditingCategory = (categoryName) => {
    setEditingCategory(categoryName);
    setTempCategoryName(categoryName);
  };

  const handleCategoryChange = (e) => {
    setTempCategoryName(e.target.value);
  };

  const saveCategoryName = (oldName) => {
    if (tempCategoryName.trim() === '') {
      setEditingCategory(null);
      return;
    }

    const updatedData = {
      categories: inventoryData.categories.map(category => {
        if (category.name !== oldName) return category;
        return {
          ...category,
          name: tempCategoryName,
          items: category.items.map(item => ({
            ...item,
            category: tempCategoryName,
            subItems: item.subItems.map(subItem => ({
              ...subItem,
              category: tempCategoryName
            }))
          }))
        };
      })
    };

    setInventoryData(updatedData);
    setEditingCategory(null);
  };

  const handleCategoryKeyDown = (e, oldName) => {
    if (e.key === 'Enter') {
      saveCategoryName(oldName);
    } else if (e.key === 'Escape') {
      setEditingCategory(null);
    }
  };
  const handleAddItem = (categoryName) => {
  const newItem = {
    id: Date.now(), // or uuid()
    name: '',
    sku: '',
    category: categoryName,
    stock: 0,
    minStock: 5,
    unit: 'Piece',
    worth: 0,
    status: 'Active',
    subItems: []
  };

  setInventoryData(prevData => {
    const updatedCategories = prevData.categories.map(cat => {
      if (cat.name !== categoryName) return cat;
      return {
        ...cat,
        items: [...cat.items, newItem]
      };
    });
    return { categories: updatedCategories };
  });
};


  if (!inventoryData || !inventoryData.categories) return null;

  return (
    <>
      {/* <div className="d-flex justify-content-between align-items-center mb-3">
        <input
          type="text"
          className="form-control w-50"
          placeholder="Search product..."
          value={searchTerm}
          onChange={(e) => setSearchTerm(e.target.value)}
        />
        <button className="btn btn-primary ms-2" onClick={handleAddProduct}>
          <i className="bi bi-plus-lg me-1"></i> Add New Product
        </button>
      </div> */}
      <div className="d-flex align-items-center" style={{ position: 'relative', width: '300px' }}>
  <input
    type="text"
    className="form-control w-50"
    placeholder="Search product..."
    value={searchTerm}
    onChange={(e) => setSearchTerm(e.target.value)}
    onFocus={() => { if (filteredProducts.length) setShowDropdown(true); }}
    onBlur={() => setTimeout(() => setShowDropdown(false), 150)} 
  />
  <button className="btn btn-primary ms-2">
    <i className="bi bi-plus-lg me-1"></i> Add New Product
  </button>

  {/* Dropdown list stays absolutely positioned relative to this container */}
  {showDropdown && filteredProducts.length > 0 && (
    <ul
      className="list-group"
      style={{
        position: 'absolute',
        top: '100%',
        left: 0,
        right: 0,
        maxHeight: '200px',
        overflowY: 'auto',
        zIndex: 1000,
      }}
    >
      {filteredProducts.map(product => (
        <li
          key={product.id}
          className="list-group-item list-group-item-action"
          style={{ cursor: 'pointer' }}
          onMouseDown={() => handleSelectProduct(product.item_name)} 
        >
          {product.item_name}
        </li>
      ))}
    </ul>
  )}
</div>


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
          <tbody>
            {inventoryData.categories.map((category) => (
              <React.Fragment key={category.name}>
                <tr className="category-row">
                  <td colSpan="8" className="bg-light fw-bold">
                    <div className="d-flex align-items-center">
                      <span
                        className="toggle-icon me-2"
                        onClick={() => toggleCategory(category.name)}
                        style={{ cursor: 'pointer' }}
                      >
                        <i className={`bi bi-chevron-${expandedCategories.includes(category.name) ? 'down' : 'right'}`}></i>
                      </span>
                      
                      {editingCategory === category.name ? (
                        <div className="d-flex align-items-center">
                          <input
                            type="text"
                            className="form-control form-control-sm d-inline-block me-2"
                            value={tempCategoryName}
                            onChange={handleCategoryChange}
                            onKeyDown={(e) => handleCategoryKeyDown(e, category.name)}
                            autoFocus
                          />
                          <button 
                            className="btn btn-sm btn-success me-1"
                            onClick={() => saveCategoryName(category.name)}
                          >
                            <i className="bi bi-check"></i>
                          </button>
                          <button 
                            className="btn btn-sm btn-secondary"
                            onClick={() => setEditingCategory(null)}
                          >
                            <i className="bi bi-x"></i>
                          </button>
                        </div>
                      ) : (
                        <div className="d-flex align-items-center">
                          <span>{category.name}</span>
                          <button 
                            className="btn btn-sm btn-link ms-2"
                            onClick={() => startEditingCategory(category.name)}
                          >
                            <i className="bi bi-pencil"></i>
                          </button>
                        </div>
                      )}
                    </div>
                    <button
                    className="btn btn-sm btn-outline-primary"
                    onClick={() => handleAddItem(category.name)}
                    title="Add Product"
                  >
                    <i className="bi bi-plus-lg"></i>
                  </button>
                  </td>
                </tr>

                {expandedCategories.includes(category.name) &&
                  category.items
                    .filter((item) => item.name.toLowerCase().includes(searchTerm.toLowerCase()))
                    .map((item) => (
                      <React.Fragment key={item.id}>
                        <tr className="main-item">
                          <td>
                            <span
                              className="toggle-icon me-2"
                              onClick={() => toggleItem(item.id)}
                              style={{ cursor: 'pointer' }}
                            >
                              {item.subItems.length > 0
                                ? expandedItems.includes(item.id)
                                  ? '▼'
                                  : '▶'
                                : ''}
                            </span>
                            <input 
                              type="text" 
                              className="form-control form-control-sm d-inline w-75" 
                              value={item.name} 
                              onChange={(e) => handleItemChange(category.name, item.id, 'name', e.target.value)} 
                              required 
                            />
                          </td>
                          <td>
                            <input 
                              type="text" 
                              className="form-control form-control-sm" 
                              value={item.sku} 
                              onChange={(e) => handleItemChange(category.name, item.id, 'sku', e.target.value)} 
                            />
                          </td>
                          <td>
                            <select 
                              className="form-select form-select-sm" 
                              value={item.category} 
                              onChange={(e) => {
                                handleItemChange(category.name, item.id, 'category', e.target.value);
                                // Update subitems' category if needed
                                if (item.subItems.length > 0) {
                                  setInventoryData(prevData => {
                                    const updatedCategories = prevData.categories.map(cat => {
                                      if (cat.name !== category.name) return cat;
                                      return {
                                        ...cat,
                                        items: cat.items.map(it => {
                                          if (it.id !== item.id) return it;
                                          return {
                                            ...it,
                                            subItems: it.subItems.map(sub => ({
                                              ...sub,
                                              category: e.target.value
                                            }))
                                          };
                                        })
                                      };
                                    });
                                    return { categories: updatedCategories };
                                  });
                                }
                              }} 
                              required
                            >
                              {inventoryData.categories.map(cat => (
                                <option key={cat.name} value={cat.name}>{cat.name}</option>
                              ))}
                            </select>
                          </td>
                          <td>
                            <input 
                              type="number" 
                              className="form-control form-control-sm" 
                              value={item.stock} 
                              onChange={(e) => handleItemChange(category.name, item.id, 'stock', parseInt(e.target.value))} 
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
                              onChange={(e) => handleItemChange(category.name, item.id, 'unit', e.target.value)} 
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
                              onChange={(e) => handleItemChange(category.name, item.id, 'worth', parseFloat(e.target.value))} 
                            />
                          </td>
                          <td>
                            <select 
                              className="form-select form-select-sm" 
                              value={item.status} 
                              onChange={(e) => handleItemChange(category.name, item.id, 'status', e.target.value)}
                            >
                              <option>Active</option>
                              <option>Inactive</option>
                            </select>
                          </td>
                          <td>
                            <button className="btn btn-sm btn-info action-btn me-1" onClick={() => setSelectedItem(item)}>
                              <i className="bi bi-info-circle"></i>
                            </button>
                            <button 
                              className="btn btn-sm btn-success action-btn me-1" 
                              onClick={() => handleAddSubItem(category.name, item.id)}
                              disabled={item.status === 'Inactive'}
                            >
                              <i className="bi bi-plus-lg"></i>
                            </button>
                            <button
                              className="btn btn-sm btn-danger action-btn"
                              onClick={() => handleDeleteItem(category.name, item.id)}
                            >
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
                                onChange={(e) => handleItemChange(category.name, item.id, 'name', e.target.value, true, subItem.id)} 
                                required 
                              />
                            </td>
                            <td>
                              <input 
                                type="text" 
                                className="form-control form-control-sm" 
                                value={subItem.sku} 
                                onChange={(e) => handleItemChange(category.name, item.id, 'sku', e.target.value, true, subItem.id)} 
                              />
                            </td>
                            <td>
                              <input 
                                type="text" 
                                className="form-control form-control-sm" 
                                value={subItem.category} 
                                readOnly 
                              />
                            </td>
                            <td>
                              <input 
                                type="number" 
                                className="form-control form-control-sm" 
                                value={subItem.stock} 
                                onChange={(e) => handleItemChange(category.name, item.id, 'stock', parseInt(e.target.value), true, subItem.id)} 
                                min="0" 
                              />
                            </td>
                            <td>
                              <select 
                                className="form-select form-select-sm" 
                                value={subItem.unit} 
                                onChange={(e) => handleItemChange(category.name, item.id, 'unit', e.target.value, true, subItem.id)} 
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
                                value={subItem.worth} 
                                onChange={(e) => handleItemChange(category.name, item.id, 'worth', parseFloat(e.target.value), true, subItem.id)} 
                              />
                            </td>
                            <td>
                              <select 
                                className="form-select form-select-sm" 
                                value={subItem.status} 
                                onChange={(e) => handleItemChange(category.name, item.id, 'status', e.target.value, true, subItem.id)}
                              >
                                <option>Active</option>
                                <option>Inactive</option>
                              </select>
                            </td>
                            <td>
                              <button
                                className="btn btn-sm btn-danger action-btn"
                                onClick={() => handleDeleteSubItem(category.name, item.id, subItem.id)}
                              >
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
        <div className="mb-3">
          <button className="btn btn-primary" onClick={handleSaveAll}>
            Save All Changes
          </button>
        </div>
        {saveMessage && (
            <div
              className={`mt-2 alert ${
                messageType === 'success' ? 'alert-success' : 'alert-danger'
              }`}
              role="alert"
            >
              {saveMessage}
            </div>
          )}
      </div>

      {/* Modal for product details */}
      <div
        className="modal fade"
        tabIndex="-1"
        role="dialog"
        aria-labelledby="productDetailsLabel"
        aria-hidden="true"
        ref={modalRef}
        onClick={handleBackdropClick}
      >
        <div className="modal-dialog modal-lg modal-dialog-centered" role="document">
          <div className="modal-content">
            <div className="modal-header">
              <h5 className="modal-title" id="productDetailsLabel">{selectedItem?.name || 'Product Details'}</h5>
              <button type="button" className="btn-close" aria-label="Close" onClick={() => setSelectedItem(null)}></button>
            </div>
            <div className="modal-body">
              <ul className="nav nav-tabs" role="tablist">
                <li className="nav-item" role="presentation">
                  <button
                    className={`nav-link ${activeTab === 'basic' ? 'active' : ''}`}
                    onClick={() => setActiveTab('basic')}
                    role="tab"
                    aria-selected={activeTab === 'basic'}
                  >
                    Basic Details
                  </button>
                </li>
                <li className="nav-item" role="presentation">
                  <button
                    className={`nav-link ${activeTab === 'extra' ? 'active' : ''}`}
                    onClick={() => setActiveTab('extra')}
                    role="tab"
                    aria-selected={activeTab === 'extra'}
                  >
                    Extra Details
                  </button>
                </li>
              </ul>
              <div className="tab-content mt-3">
                {activeTab === 'basic' && (
                  <div role="tabpanel" aria-labelledby="basic-tab">
                    <p><strong>SKU:</strong> {selectedItem?.sku}</p>
                    <p><strong>Category:</strong> {selectedItem?.category}</p>
                    <p><strong>Stock:</strong> {selectedItem?.stock}</p>
                    <p><strong>Unit:</strong> {selectedItem?.unit}</p>
                    <p><strong>Worth:</strong> ₹{selectedItem?.worth}</p>
                    <p><strong>Status:</strong> {selectedItem?.status}</p>
                  </div>
                )}
                {activeTab === 'extra' && (
                  <div role="tabpanel" aria-labelledby="extra-tab">
                    <p>Extra details can go here...</p>
                  </div>
                )}
              </div>
            </div>
            <div className="modal-footer">
              <button type="button" className="btn btn-secondary" onClick={() => setSelectedItem(null)}>Close</button>
            </div>
          </div>
        </div>
      </div>
    </>
  );
};

export default InventoryTable;