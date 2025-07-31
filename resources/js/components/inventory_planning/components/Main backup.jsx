import React, { useState, useEffect, useRef } from "react";
import "bootstrap/dist/css/bootstrap.min.css";
import "@fortawesome/fontawesome-free/css/all.min.css";
import "./InventoryDashboard.css";

function InventoryDashboard() {
  // Sample Quotation Data (same as before)
  const quotationDatabase = [
    {
      id: 'QUO-2023-001',
      project: 'Office Building Construction',
      client: 'AEC Corporation',
      date: '2023-05-19',
      status: 'approved',
      items: [
        {
          id: 1,
          name: "Structural Materials",
          availableQty: 150,
          transferredQty: 25,
          requiredQty: 175,
          vendor: "Vendor A",
          notes: "Primary materials for phase 1",
          subItems: []
        },
        {
          id: 2,
          name: "Electrical Components",
          availableQty: 85,
          transferredQty: 15,
          requiredQty: 100,
          vendor: "Vendor E",
          notes: "Wiring and electrical systems",
          subItems: []
        },
        {
          id: 3,
          name: "Electrical jests",
          availableQty: 85,
          transferredQty: 15,
          requiredQty: 100,
          vendor: "Vendor E",
          notes: "Wiring and electrical systems",
          subItems: []
        },
        {
          id: 4,
          name: "elements Components",
          availableQty: 80,
          transferredQty: 15,
          requiredQty: 100,
          vendor: "Vendor C",
          notes: "Wiring and electrical systems",
          subItems: []
        },
        {
          id: 5,
          name: "elements Components",
          availableQty: 80,
          transferredQty: 15,
          requiredQty: 100,
          vendor: "Vendor D",
          notes: "Wiring and electrical systems",
          subItems: []
        },
      ]
    },
    {
      id: 'QUO-2023-002',
      project: 'Residential Complex',
      client: 'XYZ Developers',
      date: '2023-06-10',
      status: 'pending',
      items: [
        {
          id: 1,
          name: "Flooring Materials",
          availableQty: 200,
          transferredQty: 50,
          requiredQty: 250,
          vendor: "Vendor H",
          notes: "Tiles and hardwood",
          subItems: []
        },
        {
          id: 2,
          name: "Plumbing Fixtures",
          availableQty: 75,
          transferredQty: 25,
          requiredQty: 100,
          vendor: "Vendor I",
          subItems: []
        }
      ]
    },
    {
      id: 'QUO-2023-003',
      project: 'Commercial Renovation',
      client: 'Azure Inc.',
      date: '2023-07-22',
      status: 'draft',
      items: [
        {
          id: 1,
          name: "Drywall",
          availableQty: 120,
          transferredQty: 30,
          requiredQty: 150,
          vendor: "Vendor J",
          subItems: []
        }
      ]
    }
  ];

  const vendors = [
    "Vendor A", "Vendor B", "Vendor C", "Vendor D",
    "Vendor E", "Vendor F", "Vendor G", "Vendor H",
    "Vendor I", "Vendor J", "Vendor K"
  ];

  // State management
  const [state, setState] = useState({
    currentQuotation: null,
    items: [],
    selectedItems: new Set(),
    expandedRows: new Set()
  });
  const [showAddItemModal, setShowAddItemModal] = useState(false);
  const [showAddSubItemModal, setShowAddSubItemModal] = useState({
    show: false,
    parentId: null
  });
 

  const [toast, setToast] = useState(null);
  const [searchTerm, setSearchTerm] = useState("");
  const toastTimeoutRef = useRef(null);

  // Generate a unique ID
  const generateId = () => {
    return Date.now() + Math.floor(Math.random() * 1000);
  };

  // Toggle row expansion
  const toggleRowExpand = (itemId) => {
    const newExpandedRows = new Set(state.expandedRows);
    if (newExpandedRows.has(itemId)) {
      newExpandedRows.delete(itemId);
    } else {
      newExpandedRows.add(itemId);
    }
    setState({ ...state, expandedRows: newExpandedRows });
  };

  // Expand all rows
  const expandAllRows = () => {
    const newExpandedRows = new Set();
    state.items.forEach(item => {
      newExpandedRows.add(item.id);
    });
    setState({ ...state, expandedRows: newExpandedRows });
  };

  // Collapse all rows
  const collapseAllRows = () => {
    setState({ ...state, expandedRows: new Set() });
  };

  // Load quotation into inventory (same as before)
  const loadQuotation = (quotationId) => {
    const quotation = quotationDatabase.find(q => q.id === quotationId);
    if (!quotation) return;

    setState({
      currentQuotation: quotation,
      items: JSON.parse(JSON.stringify(quotation.items)),
      selectedItems: new Set(),
      expandedRows: new Set()
    });
  };

  // Update dashboard summary (same as before)
  const getSummaryData = () => {
    let totalItems = 0;
    let totalRequired = 0;
    let totalAvailable = 0;

    const calculateItem = (item) => {
      totalItems++;
      totalRequired += item.requiredQty || 0;
      totalAvailable += (item.availableQty || 0) + (item.transferredQty || 0);

      if (item.subItems && item.subItems.length > 0) {
        item.subItems.forEach(subItem => calculateItem(subItem));
      }
    };

    state.items.forEach(item => calculateItem(item));

    const completionRate = totalRequired > 0 ? Math.round((totalAvailable / totalRequired) * 100) : 0;

    return {
      totalItems,
      totalRequired,
      totalAvailable,
      completionRate
    };
  };

  const summaryData = getSummaryData();

  // Handle item selection (same as before)
  const toggleItemSelection = (itemId, isChecked) => {
    const newSelectedItems = new Set(state.selectedItems);
    if (isChecked) {
      newSelectedItems.add(itemId);
    } else {
      newSelectedItems.delete(itemId);
    }
    setState({ ...state, selectedItems: newSelectedItems });
  };

  // Select all items (same as before)
  const selectAllItems = (isChecked) => {
    const newSelectedItems = new Set();
    if (isChecked) {
      state.items.forEach(item => {
        newSelectedItems.add(item.id);
        // Also select all sub-items
        if (item.subItems && item.subItems.length > 0) {
          item.subItems.forEach(subItem => newSelectedItems.add(subItem.id));
        }
      });
    }
    setState({ ...state, selectedItems: newSelectedItems });
  };

  // Save changes (same as before)
  const saveChanges = () => {
    console.log("Saving inventory for:", state.currentQuotation?.project);
    console.log("Inventory data:", state.items);

    setToast({
      message: `Inventory changes saved successfully for ${state.currentQuotation?.project}.`
    });

    if (toastTimeoutRef.current) {
      clearTimeout(toastTimeoutRef.current);
    }

    toastTimeoutRef.current = setTimeout(() => {
      setToast(null);
    }, 3000);
  };

  const handleFieldChange = (itemId, field, value, isSubItem = false, parentId = null) => {
    setState(prevState => {
      const newItems = [...prevState.items];

      if (isSubItem && parentId) {
        const parentIndex = newItems.findIndex(item => item.id === parentId);
        if (parentIndex !== -1) {
          const subItemIndex = newItems[parentIndex].subItems.findIndex(subItem => subItem.id === itemId);
          if (subItemIndex !== -1) {
            newItems[parentIndex].subItems[subItemIndex] = {
              ...newItems[parentIndex].subItems[subItemIndex],
              [field]: value
            };
          }
        }
      } else {
        const itemIndex = newItems.findIndex(item => item.id === itemId);
        if (itemIndex !== -1) {
          newItems[itemIndex] = {
            ...newItems[itemIndex],
            [field]: value
          };
        }
      }

      return {
        ...prevState,
        items: newItems
      };
    });
  };

  // Add new sub-item
  const handleAddSubItem = () => {
    if (!newSubItem.name.trim()) {
      alert("Sub-item name is required");
      return;
    }

    const subItemId = generateId();
    setState(prevState => {
      const newItems = [...prevState.items];
      const parentIndex = newItems.findIndex(item => item.id === showAddSubItemModal.parentId);

      if (parentIndex !== -1) {
        newItems[parentIndex].subItems = [
          ...(newItems[parentIndex].subItems || []),
          {
            ...newSubItem,
            id: subItemId
          }
        ];
      }

      return {
        ...prevState,
        items: newItems
      };
    });

    setShowAddSubItemModal({ show: false, parentId: null });
    setNewSubItem({
      id: 0,
      name: "",
      availableQty: 0,
      transferredQty: 0,
      requiredQty: 0,
      vendor: "",
      notes: ""
    });
  };

  // Remove item or sub-item
  const removeSelectedItems = () => {
    if (state.selectedItems.size === 0) return;

    setState(prevState => {
      const newItems = prevState.items.filter(item => {
        // Keep the item if it's not selected
        if (!prevState.selectedItems.has(item.id)) {
          // Filter out selected sub-items
          if (item.subItems && item.subItems.length > 0) {
            item.subItems = item.subItems.filter(subItem =>
              !prevState.selectedItems.has(subItem.id)
            );
          }
          return true;
        }
        return false;
      });

      return {
        ...prevState,
        items: newItems,
        selectedItems: new Set()
      };
    });
  };

  // Filter quotations based on search term (same as before)
  const filteredQuotations = quotationDatabase.filter(quotation =>
    quotation.project.toLowerCase().includes(searchTerm.toLowerCase()) ||
    quotation.client.toLowerCase().includes(searchTerm.toLowerCase()) ||
    quotation.id.toLowerCase().includes(searchTerm.toLowerCase())
  );

  // Load first quotation on initial render (same as before)
  useEffect(() => {
    if (quotationDatabase.length > 0 && !state.currentQuotation) {
      loadQuotation(quotationDatabase[0].id);
    }
  }, []);

  // Clean up timeout on unmount (same as before)
  useEffect(() => {
    return () => {
      if (toastTimeoutRef.current) {
        clearTimeout(toastTimeoutRef.current);
      }
    };
  }, []);

   const [newItem, setNewItem] = useState({
    id: 0,
    name: "",
    availableQty: 0,
    transferredQty: 0,
    requiredQty: 0,
    vendor: "",
    notes: "",
    subItems: []
  });
  const [newSubItem, setNewSubItem] = useState({
    id: 0,
    name: "",
    availableQty: 0,
    transferredQty: 0,
    requiredQty: 0,
    vendor: "",
    notes: ""
  });
 const AddItemModal = () => {
  const [localItem, setLocalItem] = useState({ ...newItem });

  const handleAddItem = () => {
    if (!localItem.name.trim()) {
      alert("Item name is required");
      return;
    }

    const itemId = generateId();
    setState(prevState => ({
      ...prevState,
      items: [...prevState.items, {
        ...localItem,
        id: itemId,
        subItems: []
      }]
    }));

    setShowAddItemModal(false);
    setNewItem({
      id: 0,
      name: "",
      availableQty: 0,
      transferredQty: 0,
      requiredQty: 0,
      vendor: "",
      notes: "",
      subItems: []
    });
  };

  return (
    <div className="modal-backdrop" style={{ position: 'fixed', top: 0, left: 0, right: 0, bottom: 0, backgroundColor: 'rgba(0,0,0,0.5)', zIndex: 1050, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
      <div className="modal-content" style={{ backgroundColor: 'white', padding: '20px', borderRadius: '5px', width: '600px', maxWidth: '90%' }}>
        <div className="modal-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '15px' }}>
          <h5>Add New Item</h5>
          <button onClick={() => setShowAddItemModal(false)} style={{ background: 'none', border: 'none', fontSize: '1.5rem', cursor: 'pointer' }}>&times;</button>
        </div>
        <div className="modal-body">
          <div className="form-group">
            <label>Item Name</label>
            <input
              type="text"
              className="form-control"
              value={localItem.name}
              onChange={(e) => setLocalItem({ ...localItem, name: e.target.value })}
              placeholder="Enter item name"
            />
          </div>
          <div className="row">
            <div className="col-md-4">
              <label>Available Qty</label>
              <input
                type="number"
                className="form-control"
                value={localItem.availableQty}
                onChange={(e) => setLocalItem({ ...localItem, availableQty: parseInt(e.target.value) || 0 })}
                min="0"
              />
            </div>
            <div className="col-md-4">
              <label>Transferred Qty</label>
              <input
                type="number"
                className="form-control"
                value={localItem.transferredQty}
                onChange={(e) => setLocalItem({ ...localItem, transferredQty: parseInt(e.target.value) || 0 })}
                min="0"
              />
            </div>
            <div className="col-md-4">
              <label>Required Qty</label>
              <input
                type="number"
                className="form-control"
                value={localItem.requiredQty}
                onChange={(e) => setLocalItem({ ...localItem, requiredQty: parseInt(e.target.value) || 0 })}
                min="0"
              />
            </div>
          </div>
          <div className="form-group mt-3">
            <label>Vendor</label>
            <select
              className="form-control"
              value={localItem.vendor}
              onChange={(e) => setLocalItem({ ...localItem, vendor: e.target.value })}
            >
              <option value="">Select Vendor</option>
              {vendors.map(vendor => (
                <option key={vendor} value={vendor}>{vendor}</option>
              ))}
            </select>
          </div>
          <div className="form-group mt-3">
            <label>Notes</label>
            <textarea
              className="form-control"
              value={localItem.notes}
              onChange={(e) => setLocalItem({ ...localItem, notes: e.target.value })}
              rows="3"
              placeholder="Additional notes..."
            />
          </div>
        </div>
        <div className="modal-footer" style={{ marginTop: '15px', display: 'flex', justifyContent: 'flex-end' }}>
          <button
            className="btn btn-secondary"
            onClick={() => setShowAddItemModal(false)}
            style={{ marginRight: '10px' }}
          >
            Cancel
          </button>
          <button
            className="btn btn-primary"
            onClick={handleAddItem}
          >
            Add Item
          </button>
        </div>
      </div>
    </div>
  );
};

const AddSubItemModal = () => {
  const [localSubItem, setLocalSubItem] = useState({ ...newSubItem });

  const handleAddSubItemLocal = () => {
    if (!localSubItem.name.trim()) {
      alert("Sub-item name is required");
      return;
    }

    const subItemId = generateId();
    setState(prevState => {
      const newItems = [...prevState.items];
      const parentIndex = newItems.findIndex(item => item.id === showAddSubItemModal.parentId);

      if (parentIndex !== -1) {
        newItems[parentIndex].subItems = [
          ...(newItems[parentIndex].subItems || []),
          {
            ...localSubItem,
            id: subItemId
          }
        ];
      }

      return {
        ...prevState,
        items: newItems
      };
    });

    setShowAddSubItemModal({ show: false, parentId: null });
    setNewSubItem({
      id: 0,
      name: "",
      availableQty: 0,
      transferredQty: 0,
      requiredQty: 0,
      vendor: "",
      notes: ""
    });
  };

  return (
    <div className="modal-backdrop" style={{ position: 'fixed', top: 0, left: 0, right: 0, bottom: 0, backgroundColor: 'rgba(0,0,0,0.5)', zIndex: 1050, display: 'flex', alignItems: 'center', justifyContent: 'center' }}>
      <div className="modal-content" style={{ backgroundColor: 'white', padding: '20px', borderRadius: '5px', width: '600px', maxWidth: '90%' }}>
        <div className="modal-header" style={{ display: 'flex', justifyContent: 'space-between', alignItems: 'center', marginBottom: '15px' }}>
          <h5>Add New Sub-Item</h5>
          <button onClick={() => setShowAddSubItemModal({ show: false, parentId: null })} style={{ background: 'none', border: 'none', fontSize: '1.5rem', cursor: 'pointer' }}>&times;</button>
        </div>
        <div className="modal-body">
          <div className="form-group">
            <label>Sub-Item Name</label>
            <input
              type="text"
              className="form-control"
              value={localSubItem.name}
              onChange={(e) => setLocalSubItem({ ...localSubItem, name: e.target.value })}
              placeholder="Enter sub-item name"
            />
          </div>
          <div className="row">
            <div className="col-md-4">
              <label>Available Qty</label>
              <input
                type="number"
                className="form-control"
                value={localSubItem.availableQty}
                onChange={(e) => setLocalSubItem({ ...localSubItem, availableQty: parseInt(e.target.value) || 0 })}
                min="0"
              />
            </div>
            <div className="col-md-4">
              <label>Transferred Qty</label>
              <input
                type="number"
                className="form-control"
                value={localSubItem.transferredQty}
                onChange={(e) => setLocalSubItem({ ...localSubItem, transferredQty: parseInt(e.target.value) || 0 })}
                min="0"
              />
            </div>
            <div className="col-md-4">
              <label>Required Qty</label>
              <input
                type="number"
                className="form-control"
                value={localSubItem.requiredQty}
                onChange={(e) => setLocalSubItem({ ...localSubItem, requiredQty: parseInt(e.target.value) || 0 })}
                min="0"
              />
            </div>
          </div>
          <div className="form-group mt-3">
            <label>Vendor</label>
            <select
              className="form-control"
              value={localSubItem.vendor}
              onChange={(e) => setLocalSubItem({ ...localSubItem, vendor: e.target.value })}
            >
              <option value="">Select Vendor</option>
              {vendors.map(vendor => (
                <option key={vendor} value={vendor}>{vendor}</option>
              ))}
            </select>
          </div>
          <div className="form-group mt-3">
            <label>Notes</label>
            <textarea
              className="form-control"
              value={localSubItem.notes}
              onChange={(e) => setLocalSubItem({ ...localSubItem, notes: e.target.value })}
              rows="3"
              placeholder="Additional notes..."
            />
          </div>
        </div>
        <div className="modal-footer" style={{ marginTop: '15px', display: 'flex', justifyContent: 'flex-end' }}>
          <button
            className="btn btn-secondary"
            onClick={() => setShowAddSubItemModal({ show: false, parentId: null })}
            style={{ marginRight: '10px' }}
          >
            Cancel
          </button>
          <button
            className="btn btn-primary"
            onClick={handleAddSubItemLocal}
          >
            Add Sub-Item
          </button>
        </div>
      </div>
    </div>
  );
};

  return (
    <div className="dashboard-wrapper">
      <div className="dashboard-header">
        <h4><i className="fas fa-project-diagram mr-2"></i>Project Planning Dashboard</h4>
        <div>
          <button className="btn btn-outline-secondary btn-md mr-2">
            <i className="fas fa-sync-alt mr-1"></i> Refresh
          </button>
          <button className="btn btn-success btn-md" onClick={saveChanges}>
            <i className="fas fa-save mr-1"></i> Save Changes
          </button>
        </div>
      </div>

      <div className="dashboard-container">
        {/* Quotation Panel (same as before) */}
        <div className="quotation-panel">
          <h5><i className="fas fa-file-invoice mr-2"></i>Quotations</h5>
          <div className="search-box">
            <input
              type="text"
              placeholder="Search quotations..."
              value={searchTerm}
              onChange={(e) => setSearchTerm(e.target.value)}
            />
            <button className="search-button">
              <i className="fas fa-search"></i>
            </button>
          </div>

          <div className="quotation-list">
            {filteredQuotations.map(quotation => (
              <div
                key={quotation.id}
                className={`quotation-item ${state.currentQuotation?.id === quotation.id ? 'active' : ''}`}
                onClick={() => loadQuotation(quotation.id)}
              >
                <div className="quotation-header">
                  <strong>{quotation.project}</strong>
                  <span className={`status-badge ${quotation.status}`}>
                    {quotation.status}
                  </span>
                </div>
                <div className="quotation-details">
                  <span>{quotation.client}</span>
                  <span>{quotation.date}</span>
                </div>
                <div className="quotation-footer">
                  <i className="fas fa-cubes mr-1"></i> {quotation.items.length} main categories
                </div>
              </div>
            ))}
          </div>
        </div>

        {/* Inventory Panel */}
        <div className="inventory-panel">
          <div className="inventory-header">
            <h5>
              <i className="fas fa-boxes mr-2"></i>
              {state.currentQuotation ? `${state.currentQuotation.project} Inventory` : 'Inventory Items'}
            </h5>
            <div className="inventory-actions">
              <button className="btn btn-outline-secondary btn-md mr-1" onClick={expandAllRows}>
                <i className="fas fa-expand mr-1"></i> Expand All
              </button>
              <button className="btn btn-outline-secondary btn-sm mr-1" onClick={collapseAllRows}>
                <i className="fas fa-compress mr-1"></i> Collapse All
              </button>
              <button
                className="btn btn-primary btn-sm mr-1"
                onClick={() => setShowAddItemModal(true)}
              >
                <i className="fas fa-plus mr-1"></i> Add Item
              </button>
              <button
                className="btn btn-danger btn-sm"
                disabled={state.selectedItems.size === 0}
                onClick={removeSelectedItems}
              >
                <i className="fas fa-trash-alt mr-1"></i> Remove
              </button>
            </div>
          </div>

          {/* Summary Cards (same as before) */}
          <div className="summary-cards">
            <div className="summary-card">
              <div className="card-title">Total Items</div>
              <div className="card-value">{summaryData.totalItems}</div>
            </div>
            <div className="summary-card">
              <div className="card-title">Required Qty</div>
              <div className="card-value">{summaryData.totalRequired}</div>
            </div>
            <div className="summary-card">
              <div className="card-title">Available Qty</div>
              <div className="card-value">{summaryData.totalAvailable}</div>
            </div>
            <div className="summary-card">
              <div className="card-title">Completion</div>
              <div className="card-value">{summaryData.completionRate}%</div>
            </div>
          </div>

          {/* Inventory Table */}
          <div className="inventory-table">
            <table>
              <thead>
                <tr>
                  <th className="checkbox-cell">
                    <input
                      type="checkbox"
                      checked={state.selectedItems.size === getAllItemIds(state.items).length && getAllItemIds(state.items).length > 0}
                      onChange={(e) => selectAllItems(e.target.checked)}
                    />
                  </th>
                  <th>Inventory Name</th>
                  <th>Available</th>
                  <th>Transferred</th>
                  <th>Required</th>
                  <th>Status</th>
                  <th>Vendor</th>
                  <th>Notes</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                {state.items.map(item => {
                  const totalAvailable = (item.availableQty || 0) + (item.transferredQty || 0);
                  const status = totalAvailable >= item.requiredQty ? 'complete' :
                    totalAvailable > 0 ? 'partial' : 'none';
                  const isExpanded = state.expandedRows.has(item.id);
                  const hasSubItems = item.subItems && item.subItems.length > 0;

                  return (
                    <React.Fragment key={item.id}>
                      <tr>
                        <td className="checkbox-cell">
                          <input
                            type="checkbox"
                            checked={state.selectedItems.has(item.id)}
                            onChange={(e) => toggleItemSelection(item.id, e.target.checked)}
                          />
                        </td>
                        <td>
                          <div className="item-name-cell">
                            {hasSubItems && (
                              <button
                                className="expand-button"
                                onClick={() => toggleRowExpand(item.id)}
                              >
                                <i className={`fas fa-chevron-${isExpanded ? 'down' : 'right'}`}></i>
                              </button>
                            )}
                            <input
                              type="text"
                              className="form-control-md"
                              value={item.name}
                              onChange={(e) => handleFieldChange(item.id, 'name', e.target.value)}
                            />
                          </div>
                        </td>
                        <td>
                          <input
                            type="number"
                            className="form-control-sm"
                            value={item.availableQty || 0}
                            min="0"
                            onChange={(e) => handleFieldChange(item.id, 'availableQty', parseInt(e.target.value) || 0)}
                          />
                        </td>
                        <td>
                          <input
                            type="number"
                            className="form-control-sm"
                            value={item.transferredQty || 0}
                            min="0"
                            onChange={(e) => handleFieldChange(item.id, 'transferredQty', parseInt(e.target.value) || 0)}
                          />
                        </td>
                        <td>
                          <input
                            type="number"
                            className="form-control-sm"
                            value={item.requiredQty || 0}
                            min="0"
                            onChange={(e) => handleFieldChange(item.id, 'requiredQty', parseInt(e.target.value) || 0)}
                          />
                        </td>
                        <td>
                          <span className={`status-indicator ${status}`}></span>
                          {status === 'complete' ? 'Complete' : status === 'partial' ? 'Partial' : 'None'}
                        </td>
                        <td>
                          <select
                            className="form-control-sm"
                            value={item.vendor || ''}
                            onChange={(e) => handleFieldChange(item.id, 'vendor', e.target.value)}
                          >
                            <option value="">Select Vendor</option>
                            {vendors.map(v => (
                              <option key={v} value={v}>{v}</option>
                            ))}
                          </select>
                        </td>
                        <td>
                          <input
                            type="text"
                            className="form-control-md"
                            value={item.notes || ''}
                            onChange={(e) => handleFieldChange(item.id, 'notes', e.target.value)}
                            placeholder="Add notes..."
                          />
                        </td>
                        <td>
                          <button
                          className="btn btn-sm btn-outline-primary"
                          title="Add Sub-Item"
                          onClick={() => setShowAddSubItemModal({ show: true, parentId: item.id })}
                        >
                          <i className="fas fa-plus"></i>
                        </button>
                        </td>
                      </tr>

                      {/* Sub-items rows */}
                      {isExpanded && hasSubItems && item.subItems.map(subItem => {
                        const subTotalAvailable = (subItem.availableQty || 0) + (subItem.transferredQty || 0);
                        const subStatus = subTotalAvailable >= subItem.requiredQty ? 'complete' :
                          subTotalAvailable > 0 ? 'partial' : 'none';

                        return (
                          <tr key={subItem.id} className="sub-item-row">
                            <td className="checkbox-cell">
                              <input
                                type="checkbox"
                                checked={state.selectedItems.has(subItem.id)}
                                onChange={(e) => toggleItemSelection(subItem.id, e.target.checked)}
                              />
                            </td>
                            <td>
                              <div className="sub-item-name">
                                <span className="sub-item-indent">↳</span>
                                <input
                                  type="text"
                                  className="form-control-md"
                                  value={subItem.name}
                                  onChange={(e) => handleFieldChange(subItem.id, 'name', e.target.value, true, item.id)}
                                />
                              </div>
                            </td>
                            <td>
                              <input
                                type="number"
                                className="form-control-sm"
                                value={subItem.availableQty || 0}
                                min="0"
                                onChange={(e) => handleFieldChange(subItem.id, 'availableQty', parseInt(e.target.value) || 0, true, item.id)}
                              />
                            </td>
                            <td>
                              <input
                                type="number"
                                className="form-control-sm"
                                value={subItem.transferredQty || 0}
                                min="0"
                                onChange={(e) => handleFieldChange(subItem.id, 'transferredQty', parseInt(e.target.value) || 0, true, item.id)}
                              />
                            </td>
                            <td>
                              <input
                                type="number"
                                className="form-control-sm"
                                value={subItem.requiredQty || 0}
                                min="0"
                                onChange={(e) => handleFieldChange(subItem.id, 'requiredQty', parseInt(e.target.value) || 0, true, item.id)}
                              />
                            </td>
                            <td>
                              <span className={`status-indicator ${subStatus}`}></span>
                              {subStatus === 'complete' ? 'Complete' : subStatus === 'partial' ? 'Partial' : 'None'}
                            </td>
                            <td>
                              <select
                                className="form-control-sm"
                                value={subItem.vendor || ''}
                                onChange={(e) => handleFieldChange(subItem.id, 'vendor', e.target.value, true, item.id)}
                              >
                                <option value="">Select Vendor</option>
                                {vendors.map(v => (
                                  <option key={v} value={v}>{v}</option>
                                ))}
                              </select>
                            </td>
                            <td>
                              <input
                                type="text"
                                className="form-control-md"
                                value={subItem.notes || ''}
                                onChange={(e) => handleFieldChange(subItem.id, 'notes', e.target.value, true, item.id)}
                                placeholder="Add notes..."
                              />
                            </td>
                            <td></td>
                          </tr>
                        );
                      })}
                    </React.Fragment>
                  );
                })}
              </tbody>
            </table>
          </div>
        </div>
      </div>

      {showAddItemModal && <AddItemModal />}
      {showAddSubItemModal.show && <AddSubItemModal />}

      {/* Toast notification (same as before) */}
      {toast && (
        <div className="toast-notification">
          <div className="toast-header">
            <strong>Success</strong>
            <button onClick={() => setToast(null)}>&times;</button>
          </div>
          <div className="toast-body">
            {toast.message}
          </div>
        </div>
      )}
    </div>
  );

  // Helper function to get all item IDs (including sub-items)
  function getAllItemIds(items) {
    let ids = [];
    items.forEach(item => {
      ids.push(item.id);
      if (item.subItems && item.subItems.length > 0) {
        ids = [...ids, ...item.subItems.map(subItem => subItem.id)];
      }
    });
    return ids;
  }
}

export default InventoryDashboard;