import React, { useState } from 'react';

const productList = [
  { id: 'prod1', name: 'Product 1', price: 100, gst: 18 },
  { id: 'prod2', name: 'Product 2', price: 150, gst: 12 },
  { id: 'prod3', name: 'Product 3', price: 200, gst: 5 },
];

const SaleTab = () => {
  const [rows, setRows] = useState([]);

  const handleChange = (index, field, value) => {
    const newRows = [...rows];

    if (['qty', 'disc', 'gst', 'price'].includes(field)) {
      value = Number(value);
    }

    newRows[index][field] = value;

    if (field === 'productSearch') {
      newRows[index].showSuggestions = true;
    }

    const { price, qty, disc, gst } = newRows[index];
    const discountedPrice = price * qty * (1 - disc / 100);
    const totalWithGst = discountedPrice * (1 + gst / 100);

    newRows[index].total = totalWithGst.toFixed(2);
    setRows(newRows);
  };

  const calculateTotals = () => {
    let subtotal = 0;
    let totalDiscount = 0;
    let totalGST = 0;

    rows.forEach((row) => {
      const price = parseFloat(row.price) || 0;
      const qty = row.qty || 0;
      const disc = row.disc || 0;
      const gst = row.gst || 0;

      const gross = price * qty;
      const discounted = gross * (disc / 100);
      const net = gross - discounted;
      const gstAmount = net * (gst / 100);

      subtotal += gross;
      totalDiscount += discounted;
      totalGST += gstAmount;
    });

    return {
      subtotal,
      totalDiscount,
      totalGST,
      grandTotal: subtotal - totalDiscount + totalGST,
    };
  };

  const addRow = () => {
    setRows([
      ...rows,
      {
        product: '',
        productSearch: '',
        price: 0,
        qty: 1,
        disc: 0,
        gst: 0,
        total: '0.00',
        barcode: '',
        showSuggestions: false,
      },
    ]);
  };

  const deleteRow = (index) => {
    const newRows = [...rows];
    newRows.splice(index, 1);
    setRows(newRows);
  };

  const selectProduct = (index, product) => {
    const newRows = [...rows];
    newRows[index].product = product.id;
    newRows[index].price = product.price;
    newRows[index].gst = product.gst;
    newRows[index].qty = 1;
    newRows[index].disc = 0;
    newRows[index].productSearch = product.name;
    newRows[index].showSuggestions = false;

    const total = product.price * (1 + product.gst / 100);
    newRows[index].total = total.toFixed(2);
    setRows(newRows);
  };

  const { subtotal, totalDiscount, totalGST, grandTotal } = calculateTotals();

  const completeSale = () => {
    alert('Complete sale clicked!');
  };

  return (
    <div
      className="tab-pane active"
      id="sale"
      style={{
        padding: '1rem',
        height: 'auto',
        display: 'flex',
        flexDirection: 'column',
        overflow: 'visible',
      }}
    >
      {/* Inline CSS for consistent heights and button highlight */}
      <style>{`
        /* Consistent height for inputs and selects */
        tbody tr input.form-control,
        tbody tr select.form-control,
        tbody tr td.price {
          height: 38px;
          min-height: 38px;
          padding: 6px 12px;
          font-size: 1rem;
        }

        tbody tr td.price {
          /* contentEditable cells need line-height and vertical alignment */
          line-height: 38px;
          vertical-align: middle;
        }

        /* Add Product button highlight only */
        tr.add-product-row td button.btn-primary {
          background-color: #007bff; /* Bootstrap primary */
          border-color: #0056b3;
          box-shadow: 0 0 8px rgba(0, 123, 255, 0.7);
          font-weight: 600;
          transition: box-shadow 0.3s ease;
        }

        tr.add-product-row td button.btn-primary:hover {
          box-shadow: 0 0 12px rgba(0, 123, 255, 0.9);
        }

        /* Adjust add-product-row vertical alignment */
        tr.add-product-row td {
          vertical-align: middle;
          padding-top: 0.4rem;
          padding-bottom: 0.4rem;
        }

        /* Fix for product suggestions dropdown visibility */
        .table-responsive {
          position: relative;
          overflow: visible !important;
        }
        tbody {
          position: relative;
          overflow: visible !important;
        }
      `}</style>

      <div className="row gx-3" style={{ minHeight: 0, overflow: 'visible' }}>
        <div className="col-md-8 mb-3 d-flex flex-column" style={{ minHeight: 0 }}>
          <div
            className="card border-0 rounded-3 d-flex flex-column"
            style={{ boxShadow: '0 4px 8px rgba(0,0,0,0.15)', minHeight: 0 }}
          >
            <div className="card-header bg-primary text-white py-2 px-3">
              <h6 className="mb-0">Sale Entry</h6>
            </div>

            <div className="card-body p-0 d-flex flex-column" style={{ minHeight: 0, overflow: 'visible' }}>
              <div className="table-responsive" style={{ maxHeight: 'none', overflowY: 'visible' }}>
                <table className="table table-striped table-bordered mb-0">
                  <thead
                    className="thead-light"
                    style={{ boxShadow: '0 4px 8px rgba(0,0,0,0.25)', minHeight: 0 }}
                  >
                    <tr>
                      <th>Product</th>
                      <th>Price</th>
                      <th>Qty</th>
                      <th>Disc%</th>
                      <th>GST%</th>
                      <th>Total</th>
                      <th>
                        <i className="fas fa-barcode"></i>
                      </th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    {rows.map((row, idx) => {
                      const filteredProducts = productList.filter(
                        (p) =>
                          p.name.toLowerCase().includes((row.productSearch || '').toLowerCase()) ||
                          p.id.toLowerCase().includes((row.productSearch || '').toLowerCase())
                      );

                      const selectedProduct = productList.find((p) => p.id === row.product);

                      return (
                        <tr key={idx}>
                          <td style={{ position: 'relative' }}>
                            <input
                              type="text"
                              className="form-control mb-1"
                              placeholder="Search product..."
                              value={row.productSearch || ''}
                              onChange={(e) => handleChange(idx, 'productSearch', e.target.value)}
                              onFocus={() => {
                                const newRows = [...rows];
                                newRows[idx].showSuggestions = true;
                                setRows(newRows);
                              }}
                            />
                            {row.productSearch && row.showSuggestions && (
                              <div
                                style={{
                                  position: 'absolute',
                                  background: 'white',
                                  zIndex: 1050,
                                  boxShadow: '0 2px 8px rgba(0,0,0,0.2)',
                                  maxHeight: '150px',
                                  overflowY: 'auto',
                                  width: '100%',
                                }}
                              >
                                {filteredProducts.map((product) => (
                                  <div
                                    key={product.id}
                                    className="px-2 py-1 border-bottom"
                                    style={{ cursor: 'pointer' }}
                                    onClick={() => selectProduct(idx, product)}
                                  >
                                    {product.name}
                                  </div>
                                ))}
                                {filteredProducts.length === 0 && (
                                  <div className="text-muted px-2">No products found.</div>
                                )}
                              </div>
                            )}
                            {/* {selectedProduct?.name && (
                              <div className="text-muted small">Selected: {selectedProduct.name}</div>
                            )} */}
                          </td>
                          <td
                            contentEditable
                            suppressContentEditableWarning={true}
                            className="price"
                            onBlur={(e) => handleChange(idx, 'price', e.target.textContent)}
                          >
                            {row.price}
                          </td>
                          <td>
                            <input
                              type="number"
                              className="form-control"
                              min={1}
                              value={row.qty}
                              onChange={(e) => handleChange(idx, 'qty', e.target.value)}
                            />
                          </td>
                          <td>
                            <input
                              type="number"
                              className="form-control"
                              min={0}
                              max={100}
                              value={row.disc}
                              onChange={(e) => handleChange(idx, 'disc', e.target.value)}
                            />
                          </td>
                          <td>
                            <select
                              className="form-control"
                              value={row.gst}
                              onChange={(e) => handleChange(idx, 'gst', e.target.value)}
                            >
                              <option value={0}>0%</option>
                              <option value={5}>5%</option>
                              <option value={12}>12%</option>
                              <option value={18}>18%</option>
                              <option value={28}>28%</option>
                            </select>
                          </td>
                          <td>{row.total}</td>
                          <td>
                            <input
                              type="text"
                              className="form-control"
                              placeholder="Scan..."
                              value={row.barcode}
                              onChange={(e) => handleChange(idx, 'barcode', e.target.value)}
                            />
                          </td>
                          <td>
                            <button
                              className="btn btn-danger btn-sm"
                              onClick={() => deleteRow(idx)}
                              title="Delete Row"
                            >
                              &times;
                            </button>
                          </td>
                        </tr>
                      );
                    })}

                    {/* Add Product button aligned right under Delete buttons */}
                    <tr className="add-product-row">
                      <td colSpan={7}></td>
                      <td className="text-center">
                        <button className="btn btn-primary btn-sm" onClick={addRow}>
                          Add Product
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>

        <div className="col-md-4 mb-3 d-flex flex-column" style={{ minHeight: 0 }}>
          <div
            className="card border-0 rounded-3 h-100 d-flex flex-column"
            style={{ boxShadow: '0 4px 8px rgba(0,0,0,0.15)' }}
          >
            <div className="card-header bg-secondary text-white py-2 px-3">
              <h6 className="mb-0">Invoice Total</h6>
            </div>

            <div
              className="card-body d-flex flex-column justify-content-between flex-grow-1"
              style={{ minHeight: 0 }}
            >
              <div className="mb-3" style={{ maxHeight: '150px', overflowY: 'auto' }}>
                {rows.length === 0 && <div className="text-muted">No items added yet.</div>}
                {rows.map((row, idx) => {
                  const productName =
                    productList.find((p) => p.id === row.product)?.name || row.product;
                  return (
                    <div key={idx} className="d-flex justify-content-between">
                      <span>
                        {productName} x {row.qty}
                      </span>
                      <span>₹{row.total}</span>
                    </div>
                  );
                })}
              </div>

              <div>
                <div className="d-flex justify-content-between">
                  <span>Subtotal:</span>
                  <strong>{subtotal.toFixed(2)}</strong>
                </div>
                <div className="d-flex justify-content-between">
                  <span>Discount:</span>
                  <strong>{totalDiscount.toFixed(2)}</strong>
                </div>
                <div className="d-flex justify-content-between">
                  <span>GST:</span>
                  <strong>{totalGST.toFixed(2)}</strong>
                </div>
                <hr />
                <div className="d-flex justify-content-between fs-5 fw-bold">
                  <span>Total:</span>
                  <strong>{grandTotal.toFixed(2)}</strong>
                </div>
              </div>

              <button className="btn btn-success w-100 mt-3" onClick={completeSale}>
                Complete Sale (F12)
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  );
};

export default SaleTab;
