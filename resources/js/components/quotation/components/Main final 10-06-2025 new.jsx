// src/App.jsx
import React, { useState, useEffect, useCallback } from 'react';
import './quotation.css';
import 'bootstrap/dist/css/bootstrap.min.css';
import axios from 'axios';

function Quotation() {
  // -- Customer Data --
  const [customerSearchTerm, setCustomerSearchTerm] = useState("");
  const [filteredCustomers, setFilteredCustomers] = useState([]);
  const [selectedCustomer, setSelectedCustomer] = useState(null);
  const [selectedCustomerType, setSelectedCustomerType] = useState('');
  // const [mockCustomers, setMockCustomers] = useState([]);

  // const mockCustomers = [
  //   {
  //     id: 1,
  //     name: "Tech Solutions Inc.",
  //     contactPerson: "Sarah Johnson",
  //     email: "s.johnson@techsolutions.com",
  //     phone: "555-0123",
  //     type: "Corporate",
  //     location: "New York",
  //     projects: ["Office Network Setup", "Server Upgrade"],
  //     inventory: [
  //       {
  //         id: 1,
  //         name: "Networking Equipment",
  //         available: 15,
  //         price: 1200,
  //         vendor: "Vendor A",
  //         days: 10,
  //         subItems: [
  //           {
  //             id: 11,
  //             name: "Wireless Access Points",
  //             available: 8,
  //             price: 450,
  //             vendor: "Vendor B",
  //             days: 7,
  //             subItems: [
  //               {
  //                 id: 111,
  //                 name: "Enterprise AP Pro",
  //                 available: 50,
  //                 price: 75,
  //                 vendor: "Vendor C",
  //                 days: 3
  //               },
  //               {
  //                 id: 112,
  //                 name: "Mesh Wi-Fi System",
  //                 available: 30,
  //                 price: 120,
  //                 vendor: "Vendor C",
  //                 days: 4
  //               }
  //             ]
  //           },
  //           {
  //             id: 12,
  //             name: "Switches",
  //             available: 12,
  //             price: 320,
  //             vendor: "Vendor A",
  //             days: 9,
  //             subItems: [
  //               {
  //                 id: 121,
  //                 name: "24-Port Gigabit Switch",
  //                 available: 15,
  //                 price: 220,
  //                 vendor: "Vendor A",
  //                 days: 5
  //               },
  //               {
  //                 id: 122,
  //                 name: "48-Port Managed Switch",
  //                 available: 8,
  //                 price: 520,
  //                 vendor: "Vendor A",
  //                 days: 6
  //               }
  //             ]
  //           }
  //         ]
  //       }
  //     ]
  //   },
  //   {
  //     id: 2,
  //     name: "Digital Innovations LLC",
  //     contactPerson: "Michael Chen",
  //     email: "m.chen@digitalinnov.com",
  //     phone: "555-0456",
  //     type: "Existing",
  //     location: "Chicago",
  //     projects: ["Cloud Migration", "Security Audit"],
  //     inventory: [
  //       {
  //         id: 2,
  //         name: "Cabling Solutions",
  //         available: 200,
  //         price: 950,
  //         vendor: "Vendor C",
  //         days: 8,
  //         subItems: [
  //           {
  //             id: 21,
  //             name: "Ethernet Cables",
  //             available: 600,
  //             price: 4,
  //             vendor: "Vendor C",
  //             days: 5,
  //             subItems: [
  //               {
  //                 id: 211,
  //                 name: "Cat6 - 10ft",
  //                 available: 300,
  //                 price: 4.5,
  //                 vendor: "Vendor C",
  //                 days: 2
  //               },
  //               {
  //                 id: 212,
  //                 name: "Cat6 - 50ft",
  //                 available: 150,
  //                 price: 9.5,
  //                 vendor: "Vendor C",
  //                 days: 4
  //               }
  //             ]
  //           },
  //           {
  //             id: 22,
  //             name: "Fiber Optic Cables",
  //             available: 100,
  //             price: 14.99,
  //             vendor: "Vendor B",
  //             days: 6
  //           }
  //         ]
  //       },
  //       {
  //         id: 3,
  //         name: "Patch Panels",
  //         available: 25,
  //         price: 180,
  //         vendor: "Vendor D",
  //         days: 4
  //       }
  //     ]
  //   },
  //   {
  //     id: 3,
  //     name: "Future Tech Corp",
  //     contactPerson: "Jessica Williams",
  //     email: "j.williams@futuretech.com",
  //     phone: "555-0789",
  //     type: "New",
  //     location: "San Francisco",
  //     projects: ["Full Office IT Setup"],
  //     inventory: [
  //       {
  //         id: 4,
  //         name: "Server Infrastructure",
  //         available: 10,
  //         price: 5000,
  //         vendor: "Vendor E",
  //         days: 15,
  //         subItems: [
  //           {
  //             id: 41,
  //             name: "Rack Servers",
  //             available: 5,
  //             price: 3500,
  //             vendor: "Vendor E",
  //             days: 10
  //           },
  //           {
  //             id: 42,
  //             name: "Tower Servers",
  //             available: 5,
  //             price: 3200,
  //             vendor: "Vendor F",
  //             days: 8
  //           }
  //         ]
  //       },
  //       {
  //         id: 5,
  //         name: "UPS Systems",
  //         available: 20,
  //         price: 600,
  //         vendor: "Vendor G",
  //         days: 6,
  //         subItems: [
  //           {
  //             id: 51,
  //             name: "1KVA UPS",
  //             available: 10,
  //             price: 250,
  //             vendor: "Vendor G",
  //             days: 2
  //           },
  //           {
  //             id: 52,
  //             name: "2KVA UPS",
  //             available: 10,
  //             price: 450,
  //             vendor: "Vendor G",
  //             days: 3
  //           }
  //         ]
  //       }
  //     ]
  //   }
  // ];
 const transformCustomerData = (apiData) => {
  return apiData.map(customer => ({
    ...customer,
    inventory: customer.inventory.flatMap(item => ({
      id: item.id || null, // Ensure id is included if present
      name: item['inventory name'], // Map 'inventory name' to 'name'
      available: item.items.reduce((sum, subItem) => sum + subItem.available, 0), // Sum available from items
      price: item.items.reduce((sum, subItem) => sum + subItem.price, 0), // Sum price from items
      vendor: item.items[0]?.vendor || 'N/A', // Use vendor from first item or default to 'N/A'
      days: item.items[0]?.days || 0, // Use days from first item or default to 0
      subItems: item.items || [] // Map 'items' to 'subItems'
    }))
  }));
};
const [mockCustomers, setMockCustomers] = useState([]);
const fetchInventory = async () => {
  try {
    const response = await axios.get('/api/v1/inventories/customer-inventories');
    const transformedData = transformCustomerData(response.data);
    setMockCustomers(transformedData);
    return transformedData;
  } catch (error) {
    console.error("API error:", error);
    return []; // Return empty array as fallback
  }
};

useEffect(() => {
  fetchInventory();
}, []); // Fetch data on mount

useEffect(() => {
  if (customerSearchTerm.trim() === "") {
    setFilteredCustomers([]);
    return;
  }
  
  const filtered = mockCustomers.filter(customer => 
    customer.name.toLowerCase().includes(customerSearchTerm.toLowerCase()) ||
    customer.contactPerson.toLowerCase().includes(customerSearchTerm.toLowerCase())
  );
  setFilteredCustomers(filtered);
}, [customerSearchTerm, mockCustomers]);

  const handleCustomerSelect = (customer) => {
    setSelectedCustomer(customer);
    setCustomerSearchTerm(customer.name);
    setFilteredCustomers([]);
    setSelectedCustomerType(customer.type);
    // Set inventory data to the selected customer's inventory
    setInventoryData(customer.inventory);
    // Reset selected items when customer changes
    setSelectedItems([]);
    // Reset expanded rows
    setExpandedRows(new Set());
  };

  const clearCustomerSelection = () => {
    setSelectedCustomer(null);
    setCustomerSearchTerm("");
    setSelectedCustomerType("");
    // Reset to default inventory
    setInventoryData(initialInventoryData);
    setSelectedItems([]);
    setExpandedRows(new Set());
  };
 
  //  const mockCustomers=response.data;
  // ── 1) Static Inventory Data ───────────────────────────────────────────────────────────────────────────────────
  const initialInventoryData = [
    {
      id: 1,
      name: "Networking Equipment",
      available: 15,
      price: 1200,
      vendor: "Vendor A",
      days: 10,
      subItems: [
        {
          id: 11,
          name: "Wireless Access Points",
          available: 8,
          price: 450,
          vendor: "Vendor B",
          days: 7,
          subItems: [
            {
              id: 111,
              name: "Enterprise AP Pro",
              available: 50,
              price: 75,
              vendor: "Vendor C",
              days: 3,
            },
            {
              id: 112,
              name: "Mesh Wi-Fi System",
              available: 30,
              price: 120,
              vendor: "Vendor C",
              days: 4,
            },
          ],
        },
        {
          id: 12,
          name: "Switches",
          available: 12,
          price: 320,
          vendor: "Vendor A",
          days: 9,
          subItems: [
            {
              id: 121,
              name: "24-Port Gigabit Switch",
              available: 15,
              price: 220,
              vendor: "Vendor A",
              days: 5,
            },
            {
              id: 122,
              name: "48-Port Managed Switch",
              available: 8,
              price: 520,
              vendor: "Vendor A",
              days: 6,
            },
          ],
        },
      ],
    },
    {
      id: 2,
      name: "Cabling Solutions",
      available: 120,
      price: 850,
      vendor: "Vendor C",
      days: 12,
      subItems: [
        {
          id: 21,
          name: "Ethernet Cables",
          available: 500,
          price: 3.5,
          vendor: "Vendor C",
          days: 8,
          subItems: [
            {
              id: 211,
              name: "Cat6 - 10ft",
              available: 200,
              price: 4.99,
              vendor: "Vendor C",
              days: 2,
            },
            {
              id: 212,
              name: "Cat6 - 25ft",
              available: 150,
              price: 8.99,
              vendor: "Vendor C",
              days: 3,
            },
          ],
        },
        {
          id: 22,
          name: "Fiber Optic Cables",
          available: 85,
          price: 12.5,
          vendor: "Vendor B",
          days: 11,
        },
      ],
    },
  ];

  // ── 2) State Hooks ─────────────────────────────────────────────────────────────────────────────────────────────
  const [inventoryData, setInventoryData] = useState(initialInventoryData);
  const [expandedRows, setExpandedRows] = useState(new Set());
  const [searchTerm, setSearchTerm] = useState("");
  const [vendorFilter, setVendorFilter] = useState("");
  const [selectedItems, setSelectedItems] = useState([]);
  const [includeSetup, setIncludeSetup] = useState(true);
  const [expressShipping, setExpressShipping] = useState(false);
  const [activeTab, setActiveTab] = useState('details'); // 'details', 'inventory', 'selected'
  const [projectName, setProjectName] = useState("");
  const [phone, setPhone] = useState("");
  const [email, setEmail] = useState("");
  const [location, setLocation] = useState("");

  // Update customer-related fields when selectedCustomer changes
  useEffect(() => {
    if (selectedCustomer) {
      setProjectName(selectedCustomer.projects[0] || "");
      setPhone(selectedCustomer.phone);
      setEmail(selectedCustomer.email);
      setLocation(selectedCustomer.location);
    }
  }, [selectedCustomer]);

  // ── 3) Toggle a single parent row (expand/collapse) ───────────────────────────────────────────────────────────
  const toggleRow = useCallback((id) => {
    setExpandedRows(prev => {
      const newSet = new Set(prev);
      if (newSet.has(id)) newSet.delete(id);
      else newSet.add(id);
      return newSet;
    });
  }, []);

  // ── 4) Expand All / Collapse All ──────────────────────────────────────────────────────────────────────────────
  const expandAll = () => {
    const collectParents = (items, parents = new Set()) => {
      items.forEach(item => {
        if (item.subItems && item.subItems.length) {
          parents.add(item.id);
          collectParents(item.subItems, parents);
        }
      });
      return parents;
    };
    setExpandedRows(collectParents(inventoryData));
  };

  const collapseAll = () => {
    setExpandedRows(new Set());
  };

  // ── 5) Search & Vendor Filter ─────────────────────────────────────────────────────────────────────────────────
  const rowMatchesFilter = (item) => {
    const searchTermLower = searchTerm.trim().toLowerCase();
    const matchesName = searchTerm === "" || 
      item.name.toLowerCase().includes(searchTermLower);
    
    const vendorLower = vendorFilter.toLowerCase();
    const matchesVendor = vendorFilter === "" || 
      item.vendor.toLowerCase() === vendorLower;
    
    // Check if any child matches (if there are children)
    const childMatches = item.subItems && item.subItems.length > 0 
      ? item.subItems.some(child => rowMatchesFilter(child))
      : false;

    return (matchesName && matchesVendor) || childMatches;
  };

  // ── 6) Render inventory rows recursively ────────────────────────────────────────────────────────────────────
  const renderInventoryRows = (items, level = 0, parentId = null) => {
    return items.flatMap(item => {
      const hasChildren = Array.isArray(item.subItems) && item.subItems.length > 0;
      const isExpanded = expandedRows.has(item.id);
      const visibleBySearch = rowMatchesFilter(item);

      // Only render if it passes search/vendor and if its parent is expanded (or it's a root)
      const shouldRender =
        visibleBySearch &&
        (parentId === null || expandedRows.has(parentId));

      let row = null;
      if (shouldRender) {
        row = (
          <tr
            key={item.id}
            className={`level-${level} ${hasChildren ? "parent-item" : ""}`}
            data-id={item.id}
            data-name={item.name}
            data-vendor={item.vendor}
            data-available={item.available}
            data-parent-id={parentId || ""}
          >
            <td>
              <input
                type="checkbox"
                className="form-check-input item-checkbox"
                onChange={(e) => handleItemCheckboxChange(e, item)}
                checked={selectedItems.some(si => si.id === item.id)}
              />
            </td>
            <td>
              {hasChildren ? (
                <span
                  className={`toggle-symbol ${isExpanded ? "expanded" : "collapsed"}`}
                  onClick={() => toggleRow(item.id)}
                >
                  {isExpanded ? "▼" : "▶"}
                </span>
              ) : (
                <span style={{ display: "inline-block", width: "24px" }}></span>
              )}{" "}
              {item.name}
            </td>
            <td>{item.available}</td>
            <td>
              <input
                type="number"
                className="form-control qty-input"
                min="1"
                max={item.available}
                value={
                  (() => {
                    const si = selectedItems.find(si => si.id === item.id);
                    return si ? si.qty : 1;
                  })()
                }
                onChange={(e) => handleQtyChangeInventory(item.id, e.target.value)}
                style={{ maxWidth: "100px" }}
              />
            </td>
            <td>{item.days}</td>
            <td>{item.vendor}</td>
            <td>${item.price.toFixed(2)}</td>
          </tr>
        );
      }

      // If expanded, render children as well
      const childrenRows = [];
      if (hasChildren && isExpanded) {
        childrenRows.push(...renderInventoryRows(item.subItems, level + 1, item.id));
      }

      return shouldRender
        ? [row, ...childrenRows].filter(Boolean)
        : [];
    });
  };

  // ── 7) Handle checkbox toggling for an inventory item ─────────────────────────────────────────────────────────
  const handleItemCheckboxChange = (e, item) => {
    const checked = e.target.checked;
    setSelectedItems(prev => {
      if (checked) {
        if (prev.find(si => si.id === item.id)) return prev;
        return [
          ...prev,
          {
            id: item.id,
            name: item.name,
            qty: 1,
            price: item.price,
            days: item.days,
            vendor: item.vendor,
            available: item.available,
          }
        ];
      } else {
        return prev.filter(si => si.id !== item.id);
      }
    });
  };

  // ── 8) Handle quantity change from the inventory table ────────────────────────────────────────────────────────
  const handleQtyChangeInventory = (itemId, value) => {
    const newQty = Math.max(1, parseInt(value) || 1);
    setSelectedItems(prev =>
      prev.map(si => {
        if (si.id === itemId) {
          const qty = Math.min(newQty, si.available);
          return { ...si, qty };
        }
        return si;
      })
    );
  };

  // ── 9) Handle quantity change in the Selected Items table ─────────────────────────────────────────────────────
  const handleQtyChangeSelected = (itemId, value) => {
    const newQty = Math.max(1, parseInt(value) || 1);
    setSelectedItems(prev =>
      prev.map(si => {
        if (si.id === itemId) {
          const qty = Math.min(newQty, si.available);
          return { ...si, qty };
        }
        return si;
      })
    );
  };

  // ── 10) Remove a selected item ────────────────────────────────────────────────────────────────────────────────
  const removeSelectedItem = (itemId) => {
    setSelectedItems(prev => prev.filter(si => si.id !== itemId));
  };

  // ── 11) "Select All" Checkbox Handler ─────────────────────────────────────────────────────────────────────────
  const handleSelectAll = (e) => {
    const checked = e.target.checked;
    if (checked) {
      // collect all visible items
      const allVisible = [];
      const collectVisible = (items) => {
        items.forEach(item => {
          if (rowMatchesFilter(item)) {
            allVisible.push(item);
            if (item.subItems && item.subItems.length > 0 && expandedRows.has(item.id)) {
              collectVisible(item.subItems);
            }
          }
        });
      };
      collectVisible(inventoryData);
      setSelectedItems(prev => {
        const map = new Map();
        prev.forEach(si => map.set(si.id, si));
        allVisible.forEach(item => {
          if (!map.has(item.id)) {
            map.set(item.id, {
              id: item.id,
              name: item.name,
              qty: 1,
              days: item.days,
              price: item.price,
              vendor: item.vendor,
              available: item.available,
            });
          }
        });
        return Array.from(map.values());
      });
    } else {
      setSelectedItems([]);
    }
  };

  // ── 12) Summary Calculations ───────────────────────────────────────────────────────────────────────────────────
  const [subtotal, setSubtotal] = useState(0);
  const [discount, setDiscount] = useState(0);
  const [tax, setTax] = useState(0);
  const [totalAmount, setTotalAmount] = useState(0);

  const recalcSummary = useCallback(() => {
    let base = selectedItems.reduce((sum, si) => sum + si.qty * si.price, 0);
    const disc = base * 0.05;
    const taxed = (base - disc) * 0.08;
    let shipping = 75 + (expressShipping ? 50 : 0);
    if (includeSetup) base += 150;
    const total = base - disc + taxed + shipping;

    setSubtotal(base);
    setDiscount(disc);
    setTax(taxed);
    setTotalAmount(total);
  }, [selectedItems, includeSetup, expressShipping]);

  useEffect(() => {
    recalcSummary();
  }, [selectedItems, includeSetup, expressShipping, recalcSummary]);

  // ── 13) Selected Count ─────────────────────────────────────────────────────────────────────────────────────────
  const selectedCount = selectedItems.length;

  // ── 14) Render ─────────────────────────────────────────────────────────────────────────────────────────────────
  return (
    <div className="dashboard-container">
      <div className="main-content">
        <div className="header fade-in">
          <h1>
            <i className="fas fa-file-invoice"></i> Create New Quotation
          </h1>
          <div className="user-menu">
            <div className="user-info">
              <div className="name">Alex Morgan</div>
              <div className="role">Sales Manager</div>
            </div>
            <div className="avatar">AM</div>
          </div>
        </div>

        <div className="stats-summary">
          <div className="row fade-in delay-1 mt-4">
            <div className="col-md-3">
              <div className="dashboard-card">
                <div className="card-body">
                  <div className="d-flex align-items-center">
                    <div className="bg-primary-subtle p-3 rounded me-3">
                      <i className="fas fa-file-invoice fs-2 text-primary"></i>
                    </div>
                    <div>
                      <h5 className="mb-1">Quotations</h5>
                      <h3 className="mb-0">24</h3>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div className="col-md-3">
              <div className="dashboard-card">
                <div className="card-body">
                  <div className="d-flex align-items-center">
                    <div className="bg-success-subtle p-3 rounded me-3">
                      <i className="fas fa-check-circle fs-2 text-success"></i>
                    </div>
                    <div>
                      <h5 className="mb-1">Approved</h5>
                      <h3 className="mb-0">18</h3>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div className="col-md-3">
              <div className="dashboard-card">
                <div className="card-body">
                  <div className="d-flex align-items-center">
                    <div className="bg-warning-subtle p-3 rounded me-3">
                      <i className="fas fa-clock fs-2 text-warning"></i>
                    </div>
                    <div>
                      <h5 className="mb-1">Pending</h5>
                      <h3 className="mb-0">5</h3>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            <div className="col-md-3">
              <div className="dashboard-card">
                <div className="card-body">
                  <div className="d-flex align-items-center">
                    <div className="bg-danger-subtle p-3 rounded me-3">
                      <i className="fas fa-times-circle fs-2 text-danger"></i>
                    </div>
                    <div>
                      <h5 className="mb-1">Rejected</h5>
                      <h3 className="mb-0">1</h3>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>

        <div className="dashboard-card fade-in delay-2 mt-4">
          <ul className="nav nav-tabs" id="quotationTabs" role="tablist">
            <li className="nav-item" role="presentation">
              <button
                className={`nav-link ${activeTab === 'details' ? 'active' : ''}`}
                onClick={() => setActiveTab('details')}
              >
                <i className="fas fa-info-circle me-2"></i> Quotation Details
              </button>
            </li>
            <li className="nav-item" role="presentation">
              <button
                className={`nav-link ${activeTab === 'inventory' ? 'active' : ''}`}
                onClick={() => setActiveTab('inventory')}
              >
                <i className="fas fa-boxes me-2"></i> Inventory Selection
              </button>
            </li>
            <li className="nav-item" role="presentation">
              <button
                className={`nav-link ${activeTab === 'selected' ? 'active' : ''}`}
                onClick={() => setActiveTab('selected')}
              >
                <i className="fas fa-list-check me-2"></i> Selected Items
                <span className="badge bg-primary ms-2">
                  {selectedCount} item{selectedCount !== 1 ? "s" : ""}
                </span>
              </button>
            </li>
          </ul>

          <div className="tab-content p-3">
            {/* ── Quotation Details Tab ─────────────────────────────────────────────────────────────────────── */}
            <div className={`tab-pane fade ${activeTab === 'details' ? 'show active' : ''}`}>
              <div className="row g-4 p-3 rounded shadow-sm bg-white">
                {/* Customer */}
                <div className="col-md-4 position-relative">
                  <label className="form-label mandatory">Customer</label>
                  <div className="input-group">
                    <input
                      type="text"
                      className="form-control border-primary-subtle"
                      placeholder="Search customers..."
                      value={customerSearchTerm}
                      onChange={(e) => setCustomerSearchTerm(e.target.value)}
                    />
                    <button className="btn btn-outline-primary">
                      <i className="fas fa-search"></i>
                    </button>
                    {selectedCustomer && (
                      <button 
                        className="btn btn-outline-danger" 
                        onClick={clearCustomerSelection}
                      >
                        <i className="fas fa-times"></i>
                      </button>
                    )}
                  </div>
                  
                  {/* Customer dropdown results */}
                  {filteredCustomers.length > 0 && (
                    <div className="customer-dropdown position-absolute z-3 bg-white shadow rounded mt-1 w-100">
                      {filteredCustomers.map(customer => (
                        <div 
                          key={customer.id}
                          className="p-2 border-bottom cursor-pointer hover-bg-light"
                          onClick={() => handleCustomerSelect(customer)}
                        >
                          <div className="fw-bold">{customer.name}</div>
                          <small className="text-muted">{customer.contactPerson} • {customer.type}</small>
                        </div>
                      ))}
                    </div>
                  )}
                </div>
                
                {/* Estimate Type */}
                <div className="col-md-4">
                  <label className="form-label mandatory">Estimate Type</label>
                  <select className="form-select border-primary-subtle" required>
                    <option value="">Select Type</option>
                    <option>Project</option>
                    <option>Service</option>
                    <option>Product</option>
                  </select>
                </div>

                {/* Customer Type */}
                <div className="col-md-4">
                  <label className="form-label mandatory">Customer Type</label>
                  <select 
                    className="form-select border-primary-subtle" 
                    value={selectedCustomerType}
                    onChange={(e) => setSelectedCustomerType(e.target.value)}
                    required
                  >
                    <option value="">Select Type</option>
                    <option value="New">New</option>
                    <option value="Existing">Existing</option>
                    <option value="Corporate">Corporate</option>
                  </select>
                </div>

                {/* Contact & Project */}
                <div className="col-md-6">
                  <label className="form-label">Contact Person</label>
                  <input
                    type="text"
                    className="form-control border-secondary-subtle"
                    value={selectedCustomer?.contactPerson || ""}
                    onChange={(e) => e.target.value}
                  />
                </div>

                <div className="col-md-6">
                  <label className="form-label mandatory">Project Name</label>
                  <input
                    type="text"
                    className="form-control border-secondary-subtle"
                    value={projectName}
                    onChange={(e) => setProjectName(e.target.value)}
                    required
                  />
                </div>

                {/* Contact Info */}
                <div className="col-md-4">
                  <label className="form-label">Phone No</label>
                  <input
                    type="tel"
                    className="form-control"
                    value={phone}
                    onChange={(e) => setPhone(e.target.value)}
                  />
                </div>

                <div className="col-md-4">
                  <label className="form-label">Client Mail</label>
                  <input
                    type="email"
                    className="form-control"
                    value={email}
                    onChange={(e) => setEmail(e.target.value)}
                  />
                </div>

                <div className="col-md-4">
                  <label className="form-label mandatory">Date</label>
                  <input
                    type="date"
                    className="form-control"
                    defaultValue="2025-05-24"
                    required
                  />
                </div>

                {/* Estimate & Location */}
                <div className="col-md-3">
                  <label className="form-label mandatory">Estimate No</label>
                  <input
                    type="text"
                    className="form-control"
                    defaultValue="EST-1955"
                    readOnly
                  />
                </div>

                <div className="col-md-3">
                  <label className="form-label">Location</label>
                  <input
                    type="text"
                    className="form-control"
                    value={location}
                    onChange={(e) => setLocation(e.target.value)}
                  />
                </div>

                <div className="col-md-3">
                  <label className="form-label">Venue</label>
                  <input
                    type="text"
                    className="form-control"
                    defaultValue="Main Office"
                  />
                </div>

                <div className="col-md-3">
                  <label className="form-label">Sales Person</label>
                  <input
                    type="text"
                    className="form-control"
                    defaultValue="Alex Morgan"
                    readOnly
                  />
                </div>

                {/* Status & Dates */}
                <div className="col-md-3">
                  <label className="form-label">Status</label>
                  <select className="form-select border-warning-subtle py-2">
                    <option value="pending">Pending</option>
                    <option value="completed">Completed</option>
                  </select>
                </div>

                <div className="col-md-3">
                  <label className="form-label">Start Date</label>
                  <input
                    type="date"
                    className="form-control"
                    defaultValue="2025-06-01"
                  />
                </div>

                <div className="col-md-3">
                  <label className="form-label">End Date</label>
                  <input
                    type="date"
                    className="form-control"
                    defaultValue="2025-06-15"
                  />
                </div>
              </div>

              {/* Navigation buttons for Details tab */}
              <div className="d-flex justify-content-between mt-4">
                <button 
                  className="btn btn-outline-primary" 
                  disabled
                >
                  <i className="fas fa-arrow-left me-2"></i> Back
                </button>
                <button 
                  className="btn btn-primary" 
                  onClick={() => setActiveTab('inventory')}
                >
                  Next <i className="fas fa-arrow-right ms-2"></i>
                </button>
              </div>
            </div>

            {/* ── Inventory Selection Tab ───────────────────────────────────────────────────────────────────── */}
            <div className={`tab-pane fade ${activeTab === 'inventory' ? 'show active' : ''}`}>
              <div className="search-container mb-3 d-flex align-items-center gap-2">
                <div className="search-box d-flex align-items-center">
                  <i className="fas fa-search me-2"></i>
                  <input
                    type="text"
                    className="form-control"
                    placeholder="Search inventory items..."
                    value={searchTerm}
                    onChange={(e) => setSearchTerm(e.target.value)}
                    id="searchInventory"
                  />
                </div>
                <select
                  className="form-select"
                  style={{ maxWidth: "200px" }}
                  value={vendorFilter}
                  onChange={(e) => setVendorFilter(e.target.value)}
                  id="vendorFilter"
                >
                  <option value="">All Vendors</option>
                  <option>Vendor A</option>
                  <option>Vendor B</option>
                  <option>Vendor C</option>
                  <option>Vendor D</option>
                  <option>Vendor E</option>
                  <option>Vendor F</option>
                  <option>Vendor G</option>
                </select>
                <div className="d-flex gap-2 ms-auto">
                  <button className="btn btn-sm btn-outline" onClick={expandAll}>
                    <i className="fas fa-expand"></i> Expand All
                  </button>
                  <button className="btn btn-sm btn-outline" onClick={collapseAll}>
                    <i className="fas fa-compress"></i> Collapse All
                  </button>
                </div>
                <div className="form-check">
                  <input
                    type="checkbox"
                    className="form-check-input"
                    id="selectAll"
                    onChange={handleSelectAll}
                    checked={
                      (() => {
                        const allIds = [];
                        const collectIds = (items) => {
                          items.forEach(item => {
                            if (rowMatchesFilter(item)) {
                              allIds.push(item.id);
                              if (item.subItems && item.subItems.length > 0 && expandedRows.has(item.id)) {
                                collectIds(item.subItems);
                              }
                            }
                          });
                        };
                        collectIds(inventoryData);
                        if (allIds.length === 0) return false;
                        return allIds.every(id => selectedItems.some(si => si.id === id));
                      })()
                    }
                  />
                  <label className="form-check-label" htmlFor="selectAll">
                    Select All
                  </label>
                </div>
              </div>

              <div className="table-responsive">
                <table className="table tree-table table-hover">
                  <thead className="table-light">
                    <tr>
                      <th style={{ width: "40px" }}></th>
                      <th>Inventory Item</th>
                      <th>Available</th>
                      <th>Quantity</th>
                      <th>Days</th>
                      <th>Vendor</th>
                      <th>Unit Price</th>
                    </tr>
                  </thead>
                  <tbody id="inventoryBody">
                    {renderInventoryRows(inventoryData)}
                  </tbody>
                </table>
              </div>

              {/* Navigation buttons for Inventory tab */}
              <div className="d-flex justify-content-between mt-4">
                <button 
                  className="btn btn-outline-primary" 
                  onClick={() => setActiveTab('details')}
                >
                  <i className="fas fa-arrow-left me-2"></i> Back
                </button>
                <button 
                  className="btn btn-primary" 
                  onClick={() => setActiveTab('selected')}
                >
                  Next <i className="fas fa-arrow-right ms-2"></i>
                </button>
              </div>
            </div>

            {/* ── Selected Items Tab ─────────────────────────────────────────────────────────────────────────── */}
            <div className={`tab-pane fade ${activeTab === 'selected' ? 'show active' : ''}`}>
              <div className="row">
                <div className="col-lg-8">
                  <div className="dashboard-card h-100">
                    <div className="card-body">
                      <div className="table-responsive">
                        <table className="table selected-items-table">
                          <thead>
                            <tr>
                              <th>Item Name</th>
                              <th>Quantity</th>
                              <th>Unit Price</th>
                              <th>Total</th>
                              <th>Action</th>
                            </tr>
                          </thead>
                          <tbody id="selectedItems">
                            {selectedItems.map(si => {
                              const total = (si.qty * si.price).toFixed(2);
                              return (
                                <tr key={si.id} data-id={si.id}>
                                  <td>{si.name}</td>
                                  <td>
                                    <input
                                      type="number"
                                      className="form-control qty-update"
                                      min="1"
                                      max={si.available}
                                      value={si.qty}
                                      onChange={(e) => handleQtyChangeSelected(si.id, e.target.value)}
                                      style={{ maxWidth: "80px" }}
                                    />
                                  </td>
                                  <td>${si.price.toFixed(2)}</td>
                                  <td>${total}</td>
                                  <td>
                                    <button
                                      className="btn btn-sm btn-danger remove-item"
                                      onClick={() => removeSelectedItem(si.id)}
                                    >
                                      <i className="fas fa-trash"></i>
                                    </button>
                                  </td>
                                </tr>
                              );
                            })}
                          </tbody>
                          <tfoot>
                            <tr>
                              <td colSpan="3" className="text-end fw-bold">
                                Subtotal
                              </td>
                              <td id="subtotal">${subtotal.toFixed(2)}</td>
                              <td></td>
                            </tr>
                          </tfoot>
                        </table>
                      </div>
                    </div>
                  </div>
                </div>

                <div className="col-lg-4">
                  <div className="summary-card h-100">
                    <h3 className="mb-4">
                      <i className="fas fa-receipt me-2"></i> Quotation Summary
                    </h3>
                    <div className="summary-item d-flex justify-content-between">
                      <span>Subtotal:</span>
                      <span id="summarySubtotal">${subtotal.toFixed(2)}</span>
                    </div>
                    <div className="summary-item d-flex justify-content-between">
                      <span>Discount (5%):</span>
                      <span id="discount">${discount.toFixed(2)}</span>
                    </div>
                    <div className="summary-item d-flex justify-content-between">
                      <span>Tax (8%):</span>
                      <span id="tax">${tax.toFixed(2)}</span>
                    </div>
                    <div className="summary-item d-flex justify-content-between">
                      <span>Shipping:</span>
                      <span>${expressShipping ? 125 : 75}.00</span>
                    </div>
                    <div className="summary-item d-flex justify-content-between">
                      <span>Total:</span>
                      <span id="totalAmount">${totalAmount.toFixed(2)}</span>
                    </div>

                    <div className="mt-4">
                      <div className="form-check form-switch mb-3">
                        <input
                          className="form-check-input"
                          type="checkbox"
                          id="includeSetup"
                          checked={includeSetup}
                          onChange={(e) => setIncludeSetup(e.target.checked)}
                        />
                        <label className="form-check-label" htmlFor="includeSetup">
                          Include setup fee ($150)
                        </label>
                      </div>

                      <div className="form-check form-switch">
                        <input
                          className="form-check-input"
                          type="checkbox"
                          id="expressShipping"
                          checked={expressShipping}
                          onChange={(e) => setExpressShipping(e.target.checked)}
                        />
                        <label className="form-check-label" htmlFor="expressShipping">
                          Express shipping (+$50)
                        </label>
                      </div>
                    </div>

                    <div className="d-grid mt-4">
                      <button className="btn btn-light btn-lg fw-bold">
                        <i className="fas fa-file-pdf me-2"></i> Generate Quotation
                      </button>
                    </div>
                  </div>
                </div>
              </div>

              {/* Navigation buttons for Selected Items tab */}
              <div className="d-flex justify-content-between mt-4">
                <button 
                  className="btn btn-outline-primary" 
                  onClick={() => setActiveTab('inventory')}
                >
                  <i className="fas fa-arrow-left me-2"></i> Back
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
}

export default Quotation;