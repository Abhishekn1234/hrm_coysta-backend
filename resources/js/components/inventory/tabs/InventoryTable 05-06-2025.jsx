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
  const [inventoryData, setInventoryData] = useState(data);
  const [editingCategory, setEditingCategory] = useState(null);
  const [tempCategoryName, setTempCategoryName] = useState('');
  const modalRef = useRef(null);
  const bsModal = useRef(null);
  const lastFocusedElement = useRef(null);
  const [saveMessage, setSaveMessage] = useState('');
  const [messageType, setMessageType] = useState('');
  const [selectedProduct, setSelectedProduct] = useState(null);
  const [showDropdown, setShowDropdown] = useState(false);
  const pendingSubItemsRef = useRef(null);

  // Process search data
  dataforSearch = dataforSearch.products;

  console.log("it's search list data", dataforSearch);
  const filteredProducts = (dataforSearch || []).flatMap(group => group.items || []).filter(product =>
    product.name?.toLowerCase().includes(searchTerm.toLowerCase())
  );

  // Modal setup
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

  // Modal visibility control
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

  // Accessibility: Tab key trapping in modal
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

  const handleBackdropClick = (e) => {
    if (e.target === modalRef.current) {
      bsModal.current.hide();
    }
  };

  // Product selection from search
  const handleSelectProduct = (product) => {
    setSearchTerm(product.item_name);
    const mappedProduct = {
      id: product.id,
      name: product.item_name,
      sku: product.hsn_code || '',
      category: product.stock_category || '',
      stock: product.stock_count || 0,
      minStock: 5,
      unit: product.unit || 'Piece',
      worth: product.worth || 0,
      status: 'Active',
      subItems: []
    };
    setSelectedProduct(product);
    setShowDropdown(false);
  };

  // Add new product
  const handleAddProduct = (selectedProduct = null) => {
    const newCategoryName = selectedProduct?.category || selectedProduct?.stock_category || 'New Inventory';

    const productData = selectedProduct
      ? {
          id: selectedProduct.id || Date.now(),
          name: selectedProduct.name || selectedProduct.item_name || 'New Product',
          sku: selectedProduct.sku || selectedProduct.hsn_code || '',
          category: newCategoryName,
          stock: selectedProduct.stock || selectedProduct.stock_count || 0,
          minStock: selectedProduct.minStock || 5,
          unit: selectedProduct.unit || 'Piece',
          worth: selectedProduct.worth || 0,
          status: selectedProduct.status || 'Active',
          subItems: selectedProduct.subItems || []
        }
      : {
          id: Date.now(),
          name: 'New category',
          sku: '',
          category: newCategoryName,
          stock: 0,
          minStock: 5,
          unit: 'Piece',
          worth: 0,
          status: 'Active',
          subItems: []
        };

    setInventoryData(prevData => {
      const categoryExists = prevData.categories.some(cat => cat.name === newCategoryName);
      
      if (categoryExists) {
        return {
          categories: prevData.categories.map(cat => 
            cat.name === newCategoryName
              ? { ...cat, items: [...cat.items, productData] }
              : cat
          )
        };
      } else {
        return {
          categories: [
            ...prevData.categories,
            {
              name: newCategoryName,
              items: [productData]
            }
          ]
        };
      }
    });

    if (!expandedCategories.includes(newCategoryName)) {
      toggleCategory(newCategoryName);
    }

    if (selectedProduct?.subItems?.length > 0) {
      pendingSubItemsRef.current = {
        categoryName: newCategoryName,
        itemId: productData.id,
        subItems: selectedProduct.subItems
      };
    }
  };

  // Add subitem
  const handleAddSubItem = (categoryName, itemId, subItemData = null) => {
    setInventoryData(prevData => {
      return {
        categories: prevData.categories.map(category => {
          if (category.name !== categoryName) return category;
          
          return {
            ...category,
            items: category.items.map(item => {
              if (item.id !== itemId) return item;
              
              const newSubItem = {
                id: Date.now(),
                name: subItemData?.name || `Subitem ${(item.subItems?.length || 0) + 1}`,
                sku: subItemData?.sku || '',
                category: subItemData?.category || item.category,
                stock: subItemData?.stock || 0,
                minStock: subItemData?.minStock || 5,
                unit: subItemData?.unit || item.unit,
                worth: subItemData?.worth || 0,
                status: subItemData?.status || 'Active'
              };
              
              return {
                ...item,
                subItems: [...(item.subItems || []), newSubItem]
              };
            })
          };
        })
      };
    });

    if (!expandedItems.includes(itemId)) {
      toggleItem(itemId);
    }
  };

  // Process pending subitems
  useEffect(() => {
    if (pendingSubItemsRef.current) {
      const { categoryName, itemId, subItems } = pendingSubItemsRef.current;
      
      subItems.forEach(subItem => {
        handleAddSubItem(categoryName, itemId, subItem);
      });

      pendingSubItemsRef.current = null;
    }
  }, [inventoryData]);

  // Save all changes
  const handleSaveAll = async () => {
    setSaveMessage('');

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

  // Delete item
  const handleDeleteItem = (categoryName, itemId) => {
    let updatedCategories = inventoryData.categories.map(category => {
      if (category.name !== categoryName) return category;
      return {
        ...category,
        items: category.items.filter(item => item.id !== itemId)
      };
    });

    updatedCategories = updatedCategories.filter(category => category.items.length > 0);

    setInventoryData({
      categories: updatedCategories
    });
  };

  // Delete subitem
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

  // Update item data
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

  // Category editing
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
            subItems: (item.subItems || []).map(subItem => ({
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

  // Add item to category
  const handleAddItem = (categoryName) => {
    const newItem = {
      id: Date.now(),
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
  // Function to update a field in the selectedItem
const updateField = (field, value) => {
  setSelectedItem(prev => ({
    ...prev,
    [field]: value
  }));
};

// Special function for updating dimensions
const updateDimensions = (dimension, value) => {
  setSelectedItem(prev => ({
    ...prev,
    dimensions: {
      ...prev.dimensions,
      [dimension]: value
    }
  }));
};

// Function to handle save changes
const handleSaveChanges = () => {
  // Implement your save logic here
  console.log('Saving changes:', selectedItem);
  // Then close the modal or show success message
};

  if (!inventoryData || !inventoryData.categories) return null;

  return (
    <>
      <div className="d-flex align-items-center" style={{ position: 'relative', width: '100%' }}>
        <input
          type="text"
          className="form-control w-50"
          placeholder="Search Inventory..."
          value={searchTerm}
          onChange={(e) => setSearchTerm(e.target.value)}
          onFocus={() => { if (filteredProducts.length) setShowDropdown(true); }}
          onBlur={() => setTimeout(() => setShowDropdown(false), 150)} 
        />
        <button className="btn btn-primary ms-2" onClick={() => handleAddProduct()}>
          <i className="bi bi-plus-lg me-1"></i> Add New Inventory
        </button>

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
                onMouseDown={() => handleAddProduct(product)}
              >
                {product.name}
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
                    {/* <button
                      className="btn btn-sm btn-outline-primary"
                      onClick={() => handleAddItem(category.name)}
                      title="Add Product"
                    >
                      <i className="bi bi-plus-lg"></i>
                    </button> */}
                    <button
                      className="btn btn-sm btn-outline-primary"
                      onClick={() =>  handleAddProduct()}
                      title="Add category"
                    >
                      <i className="bi bi-plus-lg"></i>
                    </button>
                   
                  </td>
                </tr>

                {expandedCategories.includes(category.name) &&
                  category.items
                    .filter((item) => (item.name || '').toLowerCase().includes(searchTerm.toLowerCase()))
                    .map((item) => (
                      <React.Fragment key={item.id}>
                       <tr className="main-item" style={{
                          margin: '8px',
                          padding: '12px',
                          borderRadius: '6px',
                          backgroundColor: '#ffffff',
                          boxShadow: '0 0 15px 5px rgba(0, 0, 0, 0.12)',
                          border: '1px solid #e0e0e0',
                        }}>
                          <td>
                            <span
                              className="toggle-icon me-2"
                              onClick={() => toggleItem(item.id)}
                              style={{ cursor: 'pointer' }}
                            >
                             {item.subItems?.length > 0
                              ? expandedItems.includes(item.id)
                                ? '▼'
                                : '▶'
                              : ''}
                            </span>
                            <input 
                              type="text" 
                              className="form-control form-control-sm d-inline w-100" 
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
                            <td >
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
        <div className="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable" role="document">
          <div className="modal-content">
            <div className="modal-header">
              <h5 className="modal-title" id="productDetailsLabel">
                Product Details - {selectedItem?.name || ''}
              </h5>
              <button
                type="button"
                className="btn-close"
                aria-label="Close"
                onClick={() => setSelectedItem(null)}
              ></button>
            </div>
            <div className="modal-body">
              <ul className="nav nav-tabs mb-4" id="detailsTab" role="tablist">
                {[
                  { key: 'basic', label: 'Basic Info' },
                  { key: 'stock', label: 'Stock & Pricing' },
                  { key: 'specs', label: 'Specifications' },
                  { key: 'vendor', label: 'Vendor Info' },
                ].map((tab) => (
                  <li className="nav-item" role="presentation" key={tab.key}>
                    <button
                      className={`nav-link ${activeTab === tab.key ? 'active' : ''}`}
                      id={`${tab.key}-tab`}
                      data-bs-toggle="tab"
                      data-bs-target={`#${tab.key}`}
                      type="button"
                      role="tab"
                      onClick={() => setActiveTab(tab.key)}
                      aria-selected={activeTab === tab.key}
                    >
                      {tab.label}
                    </button>
                  </li>
                ))}
              </ul>

              <div className="tab-content" id="detailsTabContent">
                {/* Basic Info */}
                <div className={`tab-pane fade ${activeTab === 'basic' ? 'show active' : ''}`} id="basic" role="tabpanel">
                  <div className="row">
                    <div className="col-md-6 mb-3">
                      <label className="form-label">Product Name *</label>
                      <input 
                        type="text" 
                        className="form-control" 
                        value={selectedItem?.name || ''}
                        onChange={(e) => updateField('name', e.target.value)}
                      />
                    </div>
                    <div className="col-md-6 mb-3">
                      <label className="form-label">SKU *</label>
                      <input 
                        type="text" 
                        className="form-control" 
                        value={selectedItem?.sku || ''}
                        onChange={(e) => updateField('sku', e.target.value)}
                      />
                    </div>
                    <div className="col-md-6 mb-3">
                      <label className="form-label">HSN Code *</label>
                      <input 
                        type="text" 
                        className="form-control" 
                        value={selectedItem?.hsnCode || ''}
                        onChange={(e) => updateField('hsnCode', e.target.value)}
                      />
                    </div>
                    <div className="col-md-6 mb-3">
                      <label className="form-label">Barcode</label>
                      <div className="input-group">
                        <input 
                          type="text" 
                          className="form-control" 
                          value={selectedItem?.barcode || ''}
                          onChange={(e) => updateField('barcode', e.target.value)}
                        />
                        <button className="btn btn-outline-secondary" type="button">
                          <i className="bi bi-upc-scan"></i>
                        </button>
                      </div>
                      {selectedItem?.barcode && (
                        <div className="barcode-preview mt-2">{selectedItem.barcode}</div>
                      )}
                    </div>
                    <div className="col-md-6 mb-3">
                      <label className="form-label">Category *</label>
                      <select 
                        className="form-select" 
                        value={selectedItem?.category || ''}
                        onChange={(e) => updateField('category', e.target.value)}
                        required
                      >
                        <option value="">Select Category</option>
                        <option value="Electronics">Electronics</option>
                        <option value="Furniture">Furniture</option>
                        <option value="Machinery">Machinery</option>
                      </select>
                    </div>
                    <div className="col-md-6 mb-3">
                      <label className="form-label">Unit *</label>
                      <select 
                        className="form-select" 
                        value={selectedItem?.unit || ''}
                        onChange={(e) => updateField('unit', e.target.value)}
                        required
                      >
                        <option value="">Select Unit</option>
                        <option value="Piece">Piece</option>
                        <option value="Set">Set</option>
                        <option value="Kg">Kg</option>
                        <option value="Liter">Liter</option>
                      </select>
                    </div>
                    <div className="col-md-6 mb-3">
                      <label className="form-label">Status</label>
                      <select 
                        className="form-select"
                        value={selectedItem?.status || 'Active'}
                        onChange={(e) => updateField('status', e.target.value)}
                      >
                        <option value="Active">Active</option>
                        <option value="Inactive">Inactive</option>
                        <option value="Discontinued">Discontinued</option>
                      </select>
                    </div>
                    <div className="col-md-6 mb-3">
                      <label className="form-label">Condition</label>
                      <select 
                        className="form-select"
                        value={selectedItem?.condition || 'New'}
                        onChange={(e) => updateField('condition', e.target.value)}
                      >
                        <option value="New">New</option>
                        <option value="Refurbished">Refurbished</option>
                        <option value="Used">Used</option>
                      </select>
                    </div>
                    <div className="col-12 mb-3">
                      <label className="form-label">Description</label>
                      <textarea 
                        className="form-control" 
                        rows="3"
                        value={selectedItem?.description || ''}
                        onChange={(e) => updateField('description', e.target.value)}
                      ></textarea>
                    </div>
                  </div>
                </div>

                {/* Stock & Pricing */}
                <div className={`tab-pane fade ${activeTab === 'stock' ? 'show active' : ''}`} id="stock" role="tabpanel">
                  <div className="row">
                    <div className="col-md-6 mb-3">
                      <label className="form-label">Current Stock *</label>
                      <input 
                        type="number" 
                        className="form-control" 
                        value={selectedItem?.stock || 0}
                        min="0"
                        onChange={(e) => updateField('stock', e.target.value)}
                      />
                    </div>
                    <div className="col-md-6 mb-3">
                      <label className="form-label">Minimum Stock Level</label>
                      <input 
                        type="number" 
                        className="form-control" 
                        value={selectedItem?.minStock || 0}
                        min="0"
                        onChange={(e) => updateField('minStock', e.target.value)}
                      />
                    </div>
                    <div className="col-md-6 mb-3">
                      <label className="form-label">Reorder Quantity</label>
                      <input 
                        type="number" 
                        className="form-control" 
                        value={selectedItem?.reorderQty || 0}
                        min="0"
                        onChange={(e) => updateField('reorderQty', e.target.value)}
                      />
                    </div>
                    <div className="col-md-6 mb-3">
                      <label className="form-label">Warehouse Location</label>
                      <input 
                        type="text" 
                        className="form-control" 
                        value={selectedItem?.location || ''}
                        onChange={(e) => updateField('location', e.target.value)}
                      />
                    </div>
                    <div className="col-md-6 mb-3">
                      <label className="form-label">Cost Price (₹)</label>
                      <input 
                        type="number" 
                        className="form-control" 
                        value={selectedItem?.costPrice || 0}
                        step="0.01" 
                        min="0"
                        onChange={(e) => updateField('costPrice', e.target.value)}
                      />
                    </div>
                    <div className="col-md-6 mb-3">
                      <label className="form-label">Selling Price (₹)</label>
                      <input 
                        type="number" 
                        className="form-control" 
                        value={selectedItem?.sellingPrice || 0}
                        step="0.01" 
                        min="0"
                        onChange={(e) => updateField('sellingPrice', e.target.value)}
                      />
                    </div>
                    <div className="col-md-6 mb-3">
                      <label className="form-label">Tax Rate (GST %)</label>
                      <input 
                        type="number" 
                        className="form-control" 
                        value={selectedItem?.gst || 0}
                        min="0" 
                        max="100"
                        onChange={(e) => updateField('gst', e.target.value)}
                      />
                    </div>
                    <div className="col-md-6 mb-3">
                      <label className="form-label">Rental Price (₹/day)</label>
                      <input 
                        type="number" 
                        className="form-control" 
                        value={selectedItem?.rentalPrice || 0}
                        min="0"
                        onChange={(e) => updateField('rentalPrice', e.target.value)}
                      />
                    </div>
                  </div>
                </div>

                {/* Specifications */}
                <div className={`tab-pane fade ${activeTab === 'specs' ? 'show active' : ''}`} id="specs" role="tabpanel">
                  <div className="row">
                    <div className="col-md-6 mb-3">
                      <label className="form-label">Brand</label>
                      <input 
                        type="text" 
                        className="form-control" 
                        value={selectedItem?.brand || ''}
                        onChange={(e) => updateField('brand', e.target.value)}
                      />
                    </div>
                    <div className="col-md-6 mb-3">
                      <label className="form-label">Model</label>
                      <input 
                        type="text" 
                        className="form-control" 
                        value={selectedItem?.model || ''}
                        onChange={(e) => updateField('model', e.target.value)}
                      />
                    </div>
                    <div className="col-md-6 mb-3">
                      <label className="form-label">Processor</label>
                      <input 
                        type="text" 
                        className="form-control" 
                        value={selectedItem?.processor || ''}
                        onChange={(e) => updateField('processor', e.target.value)}
                      />
                    </div>
                    <div className="col-md-6 mb-3">
                      <label className="form-label">RAM</label>
                      <input 
                        type="text" 
                        className="form-control" 
                        value={selectedItem?.ram || ''}
                        onChange={(e) => updateField('ram', e.target.value)}
                      />
                    </div>
                    <div className="col-md-6 mb-3">
                      <label className="form-label">Storage</label>
                      <input 
                        type="text" 
                        className="form-control" 
                        value={selectedItem?.storage || ''}
                        onChange={(e) => updateField('storage', e.target.value)}
                      />
                    </div>
                    <div className="col-md-6 mb-3">
                      <label className="form-label">Dimensions (cm)</label>
                      <div className="row g-2">
                        <div className="col-4">
                          <input 
                            type="number" 
                            className="form-control" 
                            placeholder="Length" 
                            value={selectedItem?.dimensions?.length || ''}
                            onChange={(e) => updateDimensions('length', e.target.value)}
                          />
                        </div>
                        <div className="col-4">
                          <input 
                            type="number" 
                            className="form-control" 
                            placeholder="Width" 
                            value={selectedItem?.dimensions?.width || ''}
                            onChange={(e) => updateDimensions('width', e.target.value)}
                          />
                        </div>
                        <div className="col-4">
                          <input 
                            type="number" 
                            className="form-control" 
                            placeholder="Height" 
                            value={selectedItem?.dimensions?.height || ''}
                            onChange={(e) => updateDimensions('height', e.target.value)}
                          />
                        </div>
                      </div>
                    </div>
                    <div className="col-md-6 mb-3">
                      <label className="form-label">Weight (kg)</label>
                      <input 
                        type="number" 
                        className="form-control" 
                        value={selectedItem?.weight || ''}
                        onChange={(e) => updateField('weight', e.target.value)}
                      />
                    </div>
                    <div className="col-md-6 mb-3">
                      <label className="form-label">Color</label>
                      <input 
                        type="text" 
                        className="form-control" 
                        value={selectedItem?.color || ''}
                        onChange={(e) => updateField('color', e.target.value)}
                      />
                    </div>
                    <div className="col-md-6 mb-3">
                      <label className="form-label">Warranty (months)</label>
                      <input 
                        type="number" 
                        className="form-control" 
                        value={selectedItem?.warranty || ''}
                        onChange={(e) => updateField('warranty', e.target.value)}
                      />
                    </div>
                  </div>
                </div>

                {/* Vendor Info */}
                <div className={`tab-pane fade ${activeTab === 'vendor' ? 'show active' : ''}`} id="vendor" role="tabpanel">
                  <div className="row">
                    <div className="col-md-6 mb-3">
                      <label className="form-label">Primary Vendor</label>
                      <select 
                        className="form-select"
                        value={selectedItem?.vendor || ''}
                        onChange={(e) => updateField('vendor', e.target.value)}
                      >
                        <option value="">Select Vendor</option>
                        <option value="Tech Suppliers Ltd.">Tech Suppliers Ltd.</option>
                        <option value="Global Electronics">Global Electronics</option>
                        <option value="Computer World">Computer World</option>
                      </select>
                    </div>
                    <div className="col-md-6 mb-3">
                      <label className="form-label">Vendor Code</label>
                      <input 
                        type="text" 
                        className="form-control" 
                        value={selectedItem?.vendorCode || ''}
                        onChange={(e) => updateField('vendorCode', e.target.value)}
                      />
                    </div>
                    <div className="col-md-6 mb-3">
                      <label className="form-label">Last Purchase Price (₹)</label>
                      <input 
                        type="number" 
                        className="form-control" 
                        value={selectedItem?.lastPurchasePrice || ''}
                        readOnly
                      />
                    </div>
                    <div className="col-md-6 mb-3">
                      <label className="form-label">Last Purchase Date</label>
                      <input 
                        type="date" 
                        className="form-control" 
                        value={selectedItem?.lastPurchaseDate || ''}
                        readOnly
                      />
                    </div>
                    <div className="col-md-6 mb-3">
                      <label className="form-label">Lead Time (days)</label>
                      <input 
                        type="number" 
                        className="form-control" 
                        value={selectedItem?.leadTime || ''}
                        onChange={(e) => updateField('leadTime', e.target.value)}
                      />
                    </div>
                    <div className="col-md-6 mb-3">
                      <label className="form-label">Minimum Order Quantity</label>
                      <input 
                        type="number" 
                        className="form-control" 
                        value={selectedItem?.minOrderQty || ''}
                        onChange={(e) => updateField('minOrderQty', e.target.value)}
                      />
                    </div>
                    <div className="col-12 mb-3">
                      <label className="form-label">Vendor Notes</label>
                      <textarea 
                        className="form-control" 
                        rows="2"
                        value={selectedItem?.vendorNotes || ''}
                        onChange={(e) => updateField('vendorNotes', e.target.value)}
                      ></textarea>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div className="modal-footer">
              <button 
                className="btn btn-primary"
                onClick={handleSaveChanges}
              >
                Save Changes
              </button>
              <button 
                className="btn btn-secondary" 
                onClick={() => setSelectedItem(null)}
              >
                Close
              </button>
            </div>
          </div>
        </div>
      </div>

    </>
  );
};

export default InventoryTable;