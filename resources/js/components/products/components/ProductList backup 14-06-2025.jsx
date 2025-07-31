// import React, { useState, useEffect } from 'react';
// import axios from 'axios';
// import {
//   Box,
//   Typography,
//   Table,
//   TableBody,
//   TableCell,
//   TableContainer,
//   TableHead,
//   TableRow,
//   Paper,
//   Avatar,
//   IconButton,
//   Drawer,
//   TextField,
//   Button,
//   Divider,
//   Stack,
// } from '@mui/material';
// import { Delete as DeleteIcon, Edit as EditIcon, Upload as UploadIcon } from '@mui/icons-material';

// const ProductList = ({ products, setProducts }) => {

//   console.log("this is list product", products);
//   // ── State for sidebar (Drawer) ──────────────────────────────────────────────
//   const [isSidebarOpen, setSidebarOpen] = useState(false);
//   const [selectedProduct, setSelectedProduct] = useState(null);
//   const [isSaving, setIsSaving] = useState(false);

//   // ── Track newly chosen file (if any) ───────────────────────────────────────
//   const [newImageFile, setNewImageFile] = useState(null);
//   const [previewUrl, setPreviewUrl] = useState('');

//   // Whenever a product is loaded into the drawer, set previewUrl to its existing image
//   useEffect(() => {
//     if (selectedProduct) {
//       if (newImageFile) {
//         // If user has already chosen a new file, preview that instead
//         setPreviewUrl(URL.createObjectURL(newImageFile));
//       } else {
//         // Otherwise show the existing URL from the product
//         setPreviewUrl(
//           selectedProduct.image
//             ? `/storage/${selectedProduct.image}`
//             : '/placeholder-product.png'
//         );
//       }
//     } else {
//       setPreviewUrl('');
//     }
//   }, [selectedProduct, newImageFile]);

//   // ── User clicks “Edit” icon ──────────────────────────────────────────────────
//   const handleEditClick = (product, index) => {
//     setSelectedProduct({ ...product, _idx: index });
//     setNewImageFile(null);
//     setSidebarOpen(true);
//   };

//   const handleDelete = async (id, index) => {

//     console.log("id",id);
//     console.log("index",index);
//   const confirmDelete = window.confirm("Are you sure you want to delete this product?");
//   if (!confirmDelete) return;

//   try {
//      console.log("id inside",id);
//     const response = await axios.delete(`/api/v1/products/delete/${id}`);
//     console.log("Delete response:", response);

//     const updated = [...products];
//     updated.splice(index, 1);
//     setProducts(updated);
//   } catch (error) {
//     console.error("Delete failed:", error);
//     // Show your existing snackbar here if needed
//   }
// };



//   // ── Track changes to text fields ─────────────────────────────────────────────
//   const handleFieldChange = (e) => {
//     const { name, value } = e.target;
//     setSelectedProduct((prev) => ({
//       ...prev,
//       [name]: value,
//     }));
//   };

//   // ── Handle new image selection ───────────────────────────────────────────────
//   const handleImageChange = (e) => {
//     if (e.target.files && e.target.files[0]) {
//       const file = e.target.files[0];
//       setNewImageFile(file);
//     }
//   };

//   // ── When “Save” is clicked ───────────────────────────────────────────────────
//   const handleUpdate = async () => {
//     if (!selectedProduct) return;
//     setIsSaving(true);

//     try {
//       // Build FormData to include text fields + new image file (if any)
//       const formData = new FormData();
//       const { id, _idx, ...fields } = selectedProduct;

//       // Append all text fields
//       Object.entries(fields).forEach(([key, val]) => {
//         formData.append(key, val ?? '');
//       });

//       // If user picked a new image file, append it under “image”
//       if (newImageFile) {
//         formData.append('image', newImageFile);
//       }

//       // To inspect FormData contents in console:
//      // console.log("this is updating data",formData);
//       for (let pair of formData.entries()) {
//         console.log(`${pair[0]}:`, pair[1]);
//       }
//       formData.append('_method', 'PUT');

//       // Axios PUT with multipart/form-data (note your route includes 'update')
//       console.log('Updating product id:', id);
//       const response = await axios.post(`/api/v1/products/update/${id}`, formData, {
//         headers: {
//           'Content-Type': 'multipart/form-data',
//         },
//       });

//       // On success, replace the item in products array
//       const updatedProducts = [...products];
//       updatedProducts[_idx] = response.data; // assume response.data is updated product JSON
//       setProducts(updatedProducts);
//       console.log("list product",products);

//       // Close drawer & reset
//       setSidebarOpen(false);
//       setSelectedProduct(null);
//       setNewImageFile(null);
//     } catch (err) {
//       console.error('Failed to update product:', err);
//       // You can show a Snackbar or Alert here if desired
//     } finally {
//       setIsSaving(false);
//     }
//   };

//   return (
//     <Box sx={{ mt: 4 }}>
//       <Typography variant="h5" gutterBottom>
//         Product List
//       </Typography>

//       {products.length === 0 ? (
//         <Typography variant="body1" sx={{ p: 2, textAlign: 'center' }}>
//           No products added yet.
//         </Typography>
//       ) : (
//         <TableContainer component={Paper} elevation={2}>
//           <Table>
//             <TableHead>
//               <TableRow>
//                 <TableCell>Image</TableCell>
//                 <TableCell>Name</TableCell>
//                 <TableCell>Price</TableCell>
//                 <TableCell>Stock</TableCell>
//                 <TableCell>Category</TableCell>
//                 <TableCell align="center">Actions</TableCell>
//               </TableRow>
//             </TableHead>

//             <TableBody>
//               {products.map((product, index) => (
//                 <TableRow key={product.id || index}>
//                   <TableCell>
//                     <Avatar
//                       src={
//                         product.image
//                           ? `/storage/${product.image}`
//                           : '/placeholder-product.png'
//                       }
//                       variant="rounded"
//                       sx={{ width: 56, height: 56 }}
//                     />
//                   </TableCell>

//                   <TableCell>
//                     <Typography fontWeight="bold">
//                       {product.name}
//                     </Typography>
//                     <Typography variant="body2" color="text.secondary">
//                       {(product.description || '').substring(0, 50)}...
//                     </Typography>
//                   </TableCell>

//                   <TableCell>${product.base_price}</TableCell>
//                   <TableCell>{product.stock}</TableCell>
//                   <TableCell>{product.category}</TableCell>

//                   <TableCell align="center">
//                     <IconButton
//                       color="primary"
//                       onClick={() => handleEditClick(product, index)}
//                       sx={{ mr: 1 }}
//                     >
//                       <EditIcon />
//                     </IconButton>
//                     <IconButton
//                       color="error"
//                       onClick={() => handleDelete(product.id, index)}
//                     >
//                       <DeleteIcon />
//                     </IconButton>
//                   </TableCell>
//                 </TableRow>
//               ))}
//             </TableBody>
//           </Table>
//         </TableContainer>
//       )}

//       {/* ─────────────── Beautiful Edit Drawer ───────────────────────────────── */}
//       <Drawer
//         anchor="right"
//         open={isSidebarOpen}
//         onClose={() => {
//           setSidebarOpen(false);
//           setSelectedProduct(null);
//           setNewImageFile(null);
//         }}
//         PaperProps={{
//           sx: {
//             width: { xs: 320, sm: 420 },
//             p: 3,
//             borderTopLeftRadius: 16,
//             borderBottomLeftRadius: 16,
//             bgcolor: 'background.paper',
//             boxShadow: 6,
//           },
//         }}
//       >
//         <Typography variant="h6" gutterBottom>
//           Edit Product
//         </Typography>
//         <Divider sx={{ mb: 2 }} />

//         {selectedProduct && (
//           <Stack spacing={2}>
//             {/* ── IMAGE PREVIEW & UPLOAD ─────────────────────────────────── */}
//             <Box
//               sx={{
//                 display: 'flex',
//                 flexDirection: 'column',
//                 alignItems: 'center',
//                 gap: 1,
//               }}
//             >
//               <Avatar
//                 src={previewUrl}
//                 variant="rounded"
//                 sx={{ width: 96, height: 96 }}
//               />
//               <Button
//                 component="label"
//                 variant="outlined"
//                 startIcon={<UploadIcon />}
//                 sx={{ textTransform: 'none' }}
//               >
//                 Re-upload Image
//                 <input
//                   type="file"
//                   accept="image/*"
//                   hidden
//                   onChange={handleImageChange}
//                 />
//               </Button>
//               <Typography variant="caption" color="text.secondary">
//                 {newImageFile ? newImageFile.name : 'Current image preview'}
//               </Typography>
//             </Box>

//             <Divider />

//             {/* ── NAME ─────────────────────────────────────────────────────── */}
//             <TextField
//               label="Name"
//               name="name"
//               fullWidth
//               value={selectedProduct.name}
//               onChange={handleFieldChange}
//               required
//             />

//             {/* ── DESCRIPTION ──────────────────────────────────────────────── */}
//             <TextField
//               label="Description"
//               name="description"
//               fullWidth
//               multiline
//               minRows={2}
//               value={selectedProduct.description || ''}
//               onChange={handleFieldChange}
//             />

//             {/* ── PRICE ────────────────────────────────────────────────────── */}
//             <TextField
//               label="Price"
//               name="price"
//               type="number"
//               fullWidth
//               value={selectedProduct.base_price}
//               onChange={handleFieldChange}
//               required
//             />

//             {/* ── CATEGORY ─────────────────────────────────────────────────── */}
//             <TextField
//               label="Category"
//               name="category"
//               fullWidth
//               value={selectedProduct.category || ''}
//               onChange={handleFieldChange}
//             />

//             {/* ── STOCK ────────────────────────────────────────────────────── */}
//             <TextField
//               label="Stock"
//               name="stock"
//               type="number"
//               fullWidth
//               value={selectedProduct.stock}
//               onChange={handleFieldChange}
//               required
//             />

//             {/* ── SKU ──────────────────────────────────────────────────────── */}
//             <TextField
//               label="SKU"
//               name="sku"
//               fullWidth
//               value={selectedProduct.sku || ''}
//               onChange={handleFieldChange}
//             />

//             {/* ── WEIGHT ───────────────────────────────────────────────────── */}
//             <TextField
//               label="Weight"
//               name="weight"
//               fullWidth
//               value={selectedProduct.weight || ''}
//               onChange={handleFieldChange}
//             />

//             {/* ── DIMENSIONS ───────────────────────────────────────────────── */}
//             <TextField
//               label="Dimensions"
//               name="dimensions"
//               fullWidth
//               value={selectedProduct.dimensions || ''}
//               onChange={handleFieldChange}
//             />

//             {/* ── HSN CODE ─────────────────────────────────────────────────── */}
//             <TextField
//               label="HSN Code"
//               name="hsn_code"
//               fullWidth
//               value={selectedProduct.hsn_code || ''}
//               onChange={handleFieldChange}
//             />

//             {/* ── BARCODE ──────────────────────────────────────────────────── */}
//             <TextField
//               label="Barcode"
//               name="barcode"
//               fullWidth
//               value={selectedProduct.barcode || ''}
//               onChange={handleFieldChange}
//             />

//             {/* ── UNIT ─────────────────────────────────────────────────────── */}
//             <TextField
//               label="Unit"
//               name="unit"
//               fullWidth
//               value={selectedProduct.unit || ''}
//               onChange={handleFieldChange}
//             />

//             {/* ── STATUS ───────────────────────────────────────────────────── */}
//             <TextField
//               label="Status"
//               name="status"
//               fullWidth
//               value={selectedProduct.status || ''}
//               onChange={handleFieldChange}
//             />

//             {/* ── CONDITION ────────────────────────────────────────────────── */}
//             <TextField
//               label="Condition"
//               name="condition"
//               fullWidth
//               value={selectedProduct.condition || ''}
//               onChange={handleFieldChange}
//             />

//             <Box sx={{ mt: 3, display: 'flex', justifyContent: 'flex-end' }}>
//               <Button
//                 onClick={() => {
//                   setSidebarOpen(false);
//                   setSelectedProduct(null);
//                   setNewImageFile(null);
//                 }}
//                 sx={{ mr: 1 }}
//               >
//                 Cancel
//               </Button>
//               <Button
//                 variant="contained"
//                 onClick={handleUpdate}
//                 disabled={isSaving}
//               >
//                 {isSaving ? 'Saving...' : 'Save'}
//               </Button>
//             </Box>
//           </Stack>
//         )}
//       </Drawer>
//     </Box>
//   );
// };

// export default ProductList;

// import React, { useState, useEffect, useRef } from 'react';
// import axios from 'axios';
// import {
//   Box,
//   Typography,
//   Table,
//   TableBody,
//   TableCell,
//   TableContainer,
//   TableHead,
//   TableRow,
//   Paper,
//   Avatar,
//   IconButton,
//   Drawer,
//   TextField,
//   Button,
//   Divider,
//   Stack,
//   Grid,
// } from '@mui/material';
// import { Delete as DeleteIcon, Edit as EditIcon, Upload as UploadIcon } from '@mui/icons-material';
// import JsBarcode from 'jsbarcode';

// const ProductList = ({ products, setProducts }) => {
//   console.log("this is list product", products);
//   // ── State for sidebar (Drawer) ──────────────────────────────────────────────
//   const [isSidebarOpen, setSidebarOpen] = useState(false);
//   const [selectedProduct, setSelectedProduct] = useState(null);
//   const [isSaving, setIsSaving] = useState(false);
//   const barcodeRef = useRef();

//   // ── Track newly chosen file (if any) ───────────────────────────────────────
//   const [newImageFile, setNewImageFile] = useState(null);
//   const [previewUrl, setPreviewUrl] = useState('');

//   // Generate barcode whenever selected product changes
//   useEffect(() => {
//     if (selectedProduct && barcodeRef.current) {
//       // Determine the barcode value
//       const barcodeValue = selectedProduct.barcode || 
//                          `PROD-${selectedProduct.id || ''}-${selectedProduct.sku || ''}`;
      
//       try {
//         // Clear any existing barcode first
//         while (barcodeRef.current.firstChild) {
//           barcodeRef.current.removeChild(barcodeRef.current.firstChild);
//         }
        
//         // Generate the visual barcode
//         JsBarcode(barcodeRef.current, barcodeValue, {
//           format: "CODE128",
//           displayValue: true,
//           width: 2,
//           height: 60,
//         });
        
//         // Update the barcode in state if it wasn't set
//         if (!selectedProduct.barcode) {
//           setSelectedProduct(prev => ({
//             ...prev,
//             barcode: barcodeValue,
//           }));
//         }
//       } catch (error) {
//         console.error("Barcode generation error:", error);
//       }
//     }
//   }, [selectedProduct]);

//   // Whenever a product is loaded into the drawer, set previewUrl to its existing image
//   useEffect(() => {
//     if (selectedProduct) {
//       if (newImageFile) {
//         // If user has already chosen a new file, preview that instead
//         setPreviewUrl(URL.createObjectURL(newImageFile));
//       } else {
//         // Otherwise show the existing URL from the product
//         setPreviewUrl(
//           selectedProduct.image
//             ? `/storage/${selectedProduct.image}`
//             : '/placeholder-product.png'
//         );
//       }
//     } else {
//       setPreviewUrl('');
//     }
//   }, [selectedProduct, newImageFile]);

//   // ── User clicks "Edit" icon ──────────────────────────────────────────────────
//   const handleEditClick = (product, index) => {
//     setSelectedProduct({ ...product, _idx: index });
//     setNewImageFile(null);
//     setSidebarOpen(true);
//   };

//   const handleDelete = async (id, index) => {
//     console.log("id",id);
//     console.log("index",index);
//     const confirmDelete = window.confirm("Are you sure you want to delete this product?");
//     if (!confirmDelete) return;

//     try {
//       console.log("id inside",id);
//       const response = await axios.delete(`/api/v1/products/delete/${id}`);
//       console.log("Delete response:", response);

//       const updated = [...products];
//       updated.splice(index, 1);
//       setProducts(updated);
//     } catch (error) {
//       console.error("Delete failed:", error);
//     }
//   };

//   // ── Track changes to text fields ─────────────────────────────────────────────
//   const handleFieldChange = (e) => {
//     const { name, value } = e.target;
//     setSelectedProduct((prev) => ({
//       ...prev,
//       [name]: value,
//     }));
//   };

//   // ── Handle new image selection ───────────────────────────────────────────────
//   const handleImageChange = (e) => {
//     if (e.target.files && e.target.files[0]) {
//       const file = e.target.files[0];
//       setNewImageFile(file);
//     }
//   };

//   const downloadBarcode = () => {
//     if (!barcodeRef.current) return;
    
//     const svg = barcodeRef.current;
//     const serializer = new XMLSerializer();
//     const source = serializer.serializeToString(svg);
//     const blob = new Blob([source], { type: "image/svg+xml;charset=utf-8" });
//     const url = URL.createObjectURL(blob);

//     const link = document.createElement("a");
//     link.href = url;
//     link.download = `barcode-${selectedProduct.sku || selectedProduct.id || 'product'}.svg`;
//     link.click();
//   };

//   // ── When "Save" is clicked ───────────────────────────────────────────────────
//   const handleUpdate = async () => {
//     if (!selectedProduct) return;
//     setIsSaving(true);

//     try {
//       // Build FormData to include text fields + new image file (if any)
//       const formData = new FormData();
//       const { id, _idx, ...fields } = selectedProduct;

//       // Append all text fields
//       Object.entries(fields).forEach(([key, val]) => {
//         formData.append(key, val ?? '');
//       });

//       // If user picked a new image file, append it under "image"
//       if (newImageFile) {
//         formData.append('image', newImageFile);
//       }

//       // To inspect FormData contents in console:
//       for (let pair of formData.entries()) {
//         console.log(`${pair[0]}:`, pair[1]);
//       }
//       formData.append('_method', 'PUT');

//       // Axios PUT with multipart/form-data (note your route includes 'update')
//       console.log('Updating product id:', id);
//       const response = await axios.post(`/api/v1/products/update/${id}`, formData, {
//         headers: {
//           'Content-Type': 'multipart/form-data',
//         },
//       });

//       // On success, replace the item in products array
//       const updatedProducts = [...products];
//       updatedProducts[_idx] = response.data; // assume response.data is updated product JSON
//       setProducts(updatedProducts);
//       console.log("list product",products);

//       // Close drawer & reset
//       setSidebarOpen(false);
//       setSelectedProduct(null);
//       setNewImageFile(null);
//     } catch (err) {
//       console.error('Failed to update product:', err);
//     } finally {
//       setIsSaving(false);
//     }
//   };

//   return (
//     <Box sx={{ mt: 4 }}>
//       <Typography variant="h5" gutterBottom>
//         Product List
//       </Typography>

//       {products.length === 0 ? (
//         <Typography variant="body1" sx={{ p: 2, textAlign: 'center' }}>
//           No products added yet.
//         </Typography>
//       ) : (
//         <TableContainer component={Paper} elevation={2}>
//           <Table>
//             <TableHead>
//               <TableRow>
//                 <TableCell>Image</TableCell>
//                 <TableCell>Name</TableCell>
//                 <TableCell>Price</TableCell>
//                 <TableCell>Stock</TableCell>
//                 <TableCell>Category</TableCell>
//                 <TableCell align="center">Actions</TableCell>
//               </TableRow>
//             </TableHead>

//             <TableBody>
//               {products.map((product, index) => (
//                 <TableRow key={product.id || index}>
//                   <TableCell>
//                     <Avatar
//                       src={
//                         product.image
//                           ? `/storage/${product.image}`
//                           : '/placeholder-product.png'
//                       }
//                       variant="rounded"
//                       sx={{ width: 56, height: 56 }}
//                     />
//                   </TableCell>

//                   <TableCell>
//                     <Typography fontWeight="bold">
//                       {product.name}
//                     </Typography>
//                     <Typography variant="body2" color="text.secondary">
//                       {(product.description || '').substring(0, 50)}...
//                     </Typography>
//                   </TableCell>

//                   <TableCell>${product.base_price}</TableCell>
//                   <TableCell>{product.stock}</TableCell>
//                   <TableCell>{product.category}</TableCell>

//                   <TableCell align="center">
//                     <IconButton
//                       color="primary"
//                       onClick={() => handleEditClick(product, index)}
//                       sx={{ mr: 1 }}
//                     >
//                       <EditIcon />
//                     </IconButton>
//                     <IconButton
//                       color="error"
//                       onClick={() => handleDelete(product.id, index)}
//                     >
//                       <DeleteIcon />
//                     </IconButton>
//                   </TableCell>
//                 </TableRow>
//               ))}
//             </TableBody>
//           </Table>
//         </TableContainer>
//       )}

//       {/* ─────────────── Beautiful Edit Drawer ───────────────────────────────── */}
//       <Drawer
//         anchor="right"
//         open={isSidebarOpen}
//         onClose={() => {
//           setSidebarOpen(false);
//           setSelectedProduct(null);
//           setNewImageFile(null);
//         }}
//         PaperProps={{
//           sx: {
//             width: { xs: 320, sm: 500 }, // Increased width to accommodate barcode
//             p: 3,
//             borderTopLeftRadius: 16,
//             borderBottomLeftRadius: 16,
//             bgcolor: 'background.paper',
//             boxShadow: 6,
//           },
//         }}
//       >
//         <Typography variant="h6" gutterBottom>
//           Edit Product
//         </Typography>
//         <Divider sx={{ mb: 2 }} />

//         {selectedProduct && (
//           <Stack spacing={2}>
//             {/* ── IMAGE PREVIEW & UPLOAD ─────────────────────────────────── */}
//             <Box
//               sx={{
//                 display: 'flex',
//                 flexDirection: 'column',
//                 alignItems: 'center',
//                 gap: 1,
//               }}
//             >
//               <Avatar
//                 src={previewUrl}
//                 variant="rounded"
//                 sx={{ width: 96, height: 96 }}
//               />
//               <Button
//                 component="label"
//                 variant="outlined"
//                 startIcon={<UploadIcon />}
//                 sx={{ textTransform: 'none' }}
//               >
//                 Re-upload Image
//                 <input
//                   type="file"
//                   accept="image/*"
//                   hidden
//                   onChange={handleImageChange}
//                 />
//               </Button>
//               <Typography variant="caption" color="text.secondary">
//                 {newImageFile ? newImageFile.name : 'Current image preview'}
//               </Typography>
//             </Box>

//             <Divider />

//             <Grid container spacing={2}>
//               {/* ── NAME ─────────────────────────────────────────────────────── */}
//               <Grid item xs={12}>
//                 <TextField
//                   label="Name"
//                   name="name"
//                   fullWidth
//                   value={selectedProduct.name}
//                   onChange={handleFieldChange}
//                   required
//                 />
//               </Grid>

//               {/* ── DESCRIPTION ──────────────────────────────────────────────── */}
//               <Grid item xs={12}>
//                 <TextField
//                   label="Description"
//                   name="description"
//                   fullWidth
//                   multiline
//                   minRows={2}
//                   value={selectedProduct.description || ''}
//                   onChange={handleFieldChange}
//                 />
//               </Grid>

//               {/* ── PRICE ────────────────────────────────────────────────────── */}
//               <Grid item xs={12} sm={6}>
//                 <TextField
//                   label="Price"
//                   name="base_price"
//                   type="number"
//                   fullWidth
//                   value={selectedProduct.base_price}
//                   onChange={handleFieldChange}
//                   required
//                 />
//               </Grid>

//               {/* ── CATEGORY ─────────────────────────────────────────────────── */}
//               <Grid item xs={12} sm={6}>
//                 <TextField
//                   label="Category"
//                   name="category"
//                   fullWidth
//                   value={selectedProduct.category || ''}
//                   onChange={handleFieldChange}
//                 />
//               </Grid>

//               {/* ── STOCK ────────────────────────────────────────────────────── */}
//               <Grid item xs={12} sm={6}>
//                 <TextField
//                   label="Stock"
//                   name="stock"
//                   type="number"
//                   fullWidth
//                   value={selectedProduct.stock}
//                   onChange={handleFieldChange}
//                   required
//                 />
//               </Grid>

//               {/* ── SKU ──────────────────────────────────────────────────────── */}
//               <Grid item xs={12} sm={6}>
//                 <TextField
//                   label="SKU"
//                   name="sku"
//                   fullWidth
//                   value={selectedProduct.sku || ''}
//                   onChange={handleFieldChange}
//                 />
//               </Grid>

//               {/* ── WEIGHT ───────────────────────────────────────────────────── */}
//               <Grid item xs={12} sm={6}>
//                 <TextField
//                   label="Weight"
//                   name="weight"
//                   fullWidth
//                   value={selectedProduct.weight || ''}
//                   onChange={handleFieldChange}
//                 />
//               </Grid>

//               {/* ── DIMENSIONS ───────────────────────────────────────────────── */}
//               <Grid item xs={12} sm={6}>
//                 <TextField
//                   label="Dimensions"
//                   name="dimensions"
//                   fullWidth
//                   value={selectedProduct.dimensions || ''}
//                   onChange={handleFieldChange}
//                 />
//               </Grid>

//               {/* ── HSN CODE ─────────────────────────────────────────────────── */}
//               <Grid item xs={12} sm={6}>
//                 <TextField
//                   label="HSN Code"
//                   name="hsn_code"
//                   fullWidth
//                   value={selectedProduct.hsn_code || ''}
//                   onChange={handleFieldChange}
//                 />
//               </Grid>

//               {/* ── BARCODE ──────────────────────────────────────────────────── */}
//               <Grid item xs={12}>
//                 <TextField
//                   label="Barcode"
//                   name="barcode"
//                   fullWidth
//                   value={selectedProduct.barcode || ''}
//                   onChange={handleFieldChange}
//                 />
//               </Grid>

//               {/* Barcode Preview */}
//               <Grid item xs={12}>
//                 <Box sx={{ mt: 2, display: 'flex', flexDirection: 'column', alignItems: 'center' }}>
//                   <Typography variant="subtitle1" gutterBottom>Barcode Preview:</Typography>
//                   <svg ref={barcodeRef} style={{ maxWidth: '100%', height: '60px' }} />
//                   <Button 
//                     variant="outlined" 
//                     onClick={downloadBarcode}
//                     sx={{ mt: 2 }}
//                   >
//                     Download Barcode
//                   </Button>
//                 </Box>
//               </Grid>

//               {/* ── UNIT ─────────────────────────────────────────────────────── */}
//               <Grid item xs={12} sm={6}>
//                 <TextField
//                   label="Unit"
//                   name="unit"
//                   fullWidth
//                   value={selectedProduct.unit || ''}
//                   onChange={handleFieldChange}
//                 />
//               </Grid>

//               {/* ── STATUS ───────────────────────────────────────────────────── */}
//               <Grid item xs={12} sm={6}>
//                 <TextField
//                   label="Status"
//                   name="status"
//                   fullWidth
//                   value={selectedProduct.status || ''}
//                   onChange={handleFieldChange}
//                 />
//               </Grid>

//               {/* ── CONDITION ────────────────────────────────────────────────── */}
//               <Grid item xs={12}>
//                 <TextField
//                   label="Condition"
//                   name="condition"
//                   fullWidth
//                   value={selectedProduct.condition || ''}
//                   onChange={handleFieldChange}
//                 />
//               </Grid>
//             </Grid>

//             <Box sx={{ mt: 3, display: 'flex', justifyContent: 'flex-end' }}>
//               <Button
//                 onClick={() => {
//                   setSidebarOpen(false);
//                   setSelectedProduct(null);
//                   setNewImageFile(null);
//                 }}
//                 sx={{ mr: 1 }}
//               >
//                 Cancel
//               </Button>
//               <Button
//                 variant="contained"
//                 onClick={handleUpdate}
//                 disabled={isSaving}
//               >
//                 {isSaving ? 'Saving...' : 'Save'}
//               </Button>
//             </Box>
//           </Stack>
//         )}
//       </Drawer>
//     </Box>
//   );
// };

// export default ProductList;



// import React, { useState, useEffect, useRef } from 'react';
// import axios from 'axios';
// import {
//   Box,
//   Typography,
//   Table,
//   TableBody,
//   TableCell,
//   TableContainer,
//   TableHead,
//   TableRow,
//   Paper,
//   Avatar,
//   IconButton,
//   Drawer,
//   TextField,
//   Button,
//   Divider,
//   Stack,
//   Grid,
// } from '@mui/material';
// import { Delete as DeleteIcon, Edit as EditIcon, Upload as UploadIcon } from '@mui/icons-material';
// import JsBarcode from 'jsbarcode';
// import { QRCodeCanvas } from 'qrcode.react'; // Import QRCodeCanvas

// const ProductList = ({ products, setProducts }) => {
//   console.log("this is list product", products);
//   // ── State for sidebar (Drawer) ──────────────────────────────────────────────
//   const [isSidebarOpen, setSidebarOpen] = useState(false);
//   const [selectedProduct, setSelectedProduct] = useState(null);
//   const [isSaving, setIsSaving] = useState(false);
//   const barcodeRef = useRef();
//   const qrCodeRef = useRef(); // Ref for QR code container

//   // ── Track newly chosen file (if any) ───────────────────────────────────────
//   const [newImageFile, setNewImageFile] = useState(null);
//   const [previewUrl, setPreviewUrl] = useState('');

//   // Generate barcode whenever selected product changes
//   useEffect(() => {
//     if (selectedProduct && barcodeRef.current) {
//       // Determine the barcode value
//       const barcodeValue = selectedProduct.barcode || 
//                          `PROD-${selectedProduct.id || ''}-${selectedProduct.sku || ''}`;
      
//       try {
//         // Clear any existing barcode first
//         while (barcodeRef.current.firstChild) {
//           barcodeRef.current.removeChild(barcodeRef.current.firstChild);
//         }
        
//         // Generate the visual barcode
//         JsBarcode(barcodeRef.current, barcodeValue, {
//           format: "CODE128",
//           displayValue: true,
//           width: 2,
//           height: 60,
//         });
        
//         // Update the barcode in state if it wasn't set
//         if (!selectedProduct.barcode) {
//           setSelectedProduct(prev => ({
//             ...prev,
//             barcode: barcodeValue,
//           }));
//         }
//       } catch (error) {
//         console.error("Barcode generation error:", error);
//       }
//     }
//   }, [selectedProduct]);

//   // Whenever a product is loaded into the drawer, set previewUrl to its existing image
//   useEffect(() => {
//     if (selectedProduct) {
//       if (newImageFile) {
//         // If user has already chosen a new file, preview that instead
//         setPreviewUrl(URL.createObjectURL(newImageFile));
//       } else {
//         // Otherwise show the existing URL from the product
//         setPreviewUrl(
//           selectedProduct.image
//             ? `/storage/${selectedProduct.image}`
//             : '/placeholder-product.png'
//         );
//       }
//     } else {
//       setPreviewUrl('');
//     }
//   }, [selectedProduct, newImageFile]);

//   // ── User clicks "Edit" icon ──────────────────────────────────────────────────
//   const handleEditClick = (product, index) => {
//     setSelectedProduct({ ...product, _idx: index });
//     setNewImageFile(null);
//     setSidebarOpen(true);
//   };

//   const handleDelete = async (id, index) => {
//     console.log("id",id);
//     console.log("index",index);
//     const confirmDelete = window.confirm("Are you sure you want to delete this product?");
//     if (!confirmDelete) return;

//     try {
//       console.log("id inside",id);
//       const response = await axios.delete(`/api/v1/products/delete/${id}`);
//       console.log("Delete response:", response);

//       const updated = [...products];
//       updated.splice(index, 1);
//       setProducts(updated);
//     } catch (error) {
//       console.error("Delete failed:", error);
//     }
//   };

//   // ── Track changes to text fields ─────────────────────────────────────────────
//   const handleFieldChange = (e) => {
//     const { name, value } = e.target;
//     setSelectedProduct((prev) => ({
//       ...prev,
//       [name]: value,
//     }));
//   };

//   // ── Handle new image selection ───────────────────────────────────────────────
//   const handleImageChange = (e) => {
//     if (e.target.files && e.target.files[0]) {
//       const file = e.target.files[0];
//       setNewImageFile(file);
//     }
//   };

//   const downloadBarcode = () => {
//     if (!barcodeRef.current) return;
    
//     const svg = barcodeRef.current;
//     const serializer = new XMLSerializer();
//     const source = serializer.serializeToString(svg);
//     const blob = new Blob([source], { type: "image/svg+xml;charset=utf-8" });
//     const url = URL.createObjectURL(blob);

//     const link = document.createElement("a");
//     link.href = url;
//     link.download = `barcode-${selectedProduct.sku || selectedProduct.id || 'product'}.svg`;
//     link.click();
//   };

//   // Function to download QR code
//   const downloadQR = () => {
//     const canvas = qrCodeRef.current?.querySelector("canvas");
//     if (canvas) {
//       const url = canvas.toDataURL("image/png");
//       const link = document.createElement("a");
//       link.href = url;
//       link.download = `qrcode-${selectedProduct.sku || selectedProduct.id || 'product'}.png`;
//       link.click();
//     }
//   };

//   // ── When "Save" is clicked ───────────────────────────────────────────────────
//   const handleUpdate = async () => {
//     if (!selectedProduct) return;
//     setIsSaving(true);

//     try {
//       // Build FormData to include text fields + new image file (if any)
//       const formData = new FormData();
//       const { id, _idx, ...fields } = selectedProduct;

//       // Append all text fields
//       Object.entries(fields).forEach(([key, val]) => {
//         formData.append(key, val ?? '');
//       });

//       // If user picked a new image file, append it under "image"
//       if (newImageFile) {
//         formData.append('image', newImageFile);
//       }

//       // To inspect FormData contents in console:
//       for (let pair of formData.entries()) {
//         console.log(`${pair[0]}:`, pair[1]);
//       }
//       formData.append('_method', 'PUT');

//       // Axios PUT with multipart/form-data (note your route includes 'update')
//       console.log('Updating product id:', id);
//       const response = await axios.post(`/api/v1/products/update/${id}`, formData, {
//         headers: {
//           'Content-Type': 'multipart/form-data',
//         },
//       });

//       // On success, replace the item in products array
//       const updatedProducts = [...products];
//       updatedProducts[_idx] = response.data; // assume response.data is updated product JSON
//       setProducts(updatedProducts);
//       console.log("list product",products);

//       // Close drawer & reset
//       setSidebarOpen(false);
//       setSelectedProduct(null);
//       setNewImageFile(null);
//     } catch (err) {
//       console.error('Failed to update product:', err);
//     } finally {
//       setIsSaving(false);
//     }
//   };

//   return (
//     <Box sx={{ mt: 4 }}>
//       <Typography variant="h5" gutterBottom>
//         Product List
//       </Typography>

//       {products.length === 0 ? (
//         <Typography variant="body1" sx={{ p: 2, textAlign: 'center' }}>
//           No products added yet.
//         </Typography>
//       ) : (
//         <TableContainer component={Paper} elevation={2}>
//           <Table>
//             <TableHead>
//               <TableRow>
//                 <TableCell>Image</TableCell>
//                 <TableCell>Name</TableCell>
//                 <TableCell>Price</TableCell>
//                 <TableCell>Stock</TableCell>
//                 <TableCell>Category</TableCell>
//                 <TableCell align="center">Actions</TableCell>
//               </TableRow>
//             </TableHead>

//             <TableBody>
//               {products.map((product, index) => (
//                 <TableRow key={product.id || index}>
//                   <TableCell>
//                     <Avatar
//                       src={
//                         product.image
//                           ? `/storage/${product.image}`
//                           : '/placeholder-product.png'
//                       }
//                       variant="rounded"
//                       sx={{ width: 56, height: 56 }}
//                     />
//                   </TableCell>

//                   <TableCell>
//                     <Typography fontWeight="bold">
//                       {product.name}
//                     </Typography>
//                     <Typography variant="body2" color="text.secondary">
//                       {(product.description || '').substring(0, 50)}...
//                     </Typography>
//                   </TableCell>

//                   <TableCell>${product.base_price}</TableCell>
//                   <TableCell>{product.stock}</TableCell>
//                   <TableCell>{product.category}</TableCell>

//                   <TableCell align="center">
//                     <IconButton
//                       color="primary"
//                       onClick={() => handleEditClick(product, index)}
//                       sx={{ mr: 1 }}
//                     >
//                       <EditIcon />
//                     </IconButton>
//                     <IconButton
//                       color="error"
//                       onClick={() => handleDelete(product.id, index)}
//                     >
//                       <DeleteIcon />
//                     </IconButton>
//                   </TableCell>
//                 </TableRow>
//               ))}
//             </TableBody>
//           </Table>
//         </TableContainer>
//       )}

//       {/* ─────────────── Beautiful Edit Drawer ───────────────────────────────── */}
//       <Drawer
//         anchor="right"
//         open={isSidebarOpen}
//         onClose={() => {
//           setSidebarOpen(false);
//           setSelectedProduct(null);
//           setNewImageFile(null);
//         }}
//         PaperProps={{
//           sx: {
//             width: { xs: 320, sm: 600 }, // Increased width to accommodate QR code
//             p: 3,
//             borderTopLeftRadius: 16,
//             borderBottomLeftRadius: 16,
//             bgcolor: 'background.paper',
//             boxShadow: 6,
//           },
//         }}
//       >
//         <Typography variant="h6" gutterBottom>
//           Edit Product
//         </Typography>
//         <Divider sx={{ mb: 2 }} />

//         {selectedProduct && (
//           <Stack spacing={2}>
//             {/* ── IMAGE PREVIEW & UPLOAD ─────────────────────────────────── */}
//             <Box
//               sx={{
//                 display: 'flex',
//                 flexDirection: 'column',
//                 alignItems: 'center',
//                 gap: 1,
//               }}
//             >
//               <Avatar
//                 src={previewUrl}
//                 variant="rounded"
//                 sx={{ width: 96, height: 96 }}
//               />
//               <Button
//                 component="label"
//                 variant="outlined"
//                 startIcon={<UploadIcon />}
//                 sx={{ textTransform: 'none' }}
//               >
//                 Re-upload Image
//                 <input
//                   type="file"
//                   accept="image/*"
//                   hidden
//                   onChange={handleImageChange}
//                 />
//               </Button>
//               <Typography variant="caption" color="text.secondary">
//                 {newImageFile ? newImageFile.name : 'Current image preview'}
//               </Typography>
//             </Box>

//             <Divider />

//             <Grid container spacing={2}>
//               {/* ── NAME ─────────────────────────────────────────────────────── */}
//               <Grid item xs={12}>
//                 <TextField
//                   label="Name"
//                   name="name"
//                   fullWidth
//                   value={selectedProduct.name}
//                   onChange={handleFieldChange}
//                   required
//                 />
//               </Grid>

//               {/* ── DESCRIPTION ──────────────────────────────────────────────── */}
//               <Grid item xs={12}>
//                 <TextField
//                   label="Description"
//                   name="description"
//                   fullWidth
//                   multiline
//                   minRows={2}
//                   value={selectedProduct.description || ''}
//                   onChange={handleFieldChange}
//                 />
//               </Grid>

//               {/* ── PRICE ────────────────────────────────────────────────────── */}
//               <Grid item xs={12} sm={6}>
//                 <TextField
//                   label="Price"
//                   name="base_price"
//                   type="number"
//                   fullWidth
//                   value={selectedProduct.base_price}
//                   onChange={handleFieldChange}
//                   required
//                 />
//               </Grid>

//               {/* ── CATEGORY ─────────────────────────────────────────────────── */}
//               <Grid item xs={12} sm={6}>
//                 <TextField
//                   label="Category"
//                   name="category"
//                   fullWidth
//                   value={selectedProduct.category || ''}
//                   onChange={handleFieldChange}
//                 />
//               </Grid>

//               {/* ── STOCK ────────────────────────────────────────────────────── */}
//               <Grid item xs={12} sm={6}>
//                 <TextField
//                   label="Stock"
//                   name="stock"
//                   type="number"
//                   fullWidth
//                   value={selectedProduct.stock}
//                   onChange={handleFieldChange}
//                   required
//                 />
//               </Grid>

//               {/* ── SKU ──────────────────────────────────────────────────────── */}
//               <Grid item xs={12} sm={6}>
//                 <TextField
//                   label="SKU"
//                   name="sku"
//                   fullWidth
//                   value={selectedProduct.sku || ''}
//                   onChange={handleFieldChange}
//                 />
//               </Grid>

//               {/* ── WEIGHT ───────────────────────────────────────────────────── */}
//               <Grid item xs={12} sm={6}>
//                 <TextField
//                   label="Weight"
//                   name="weight"
//                   fullWidth
//                   value={selectedProduct.weight || ''}
//                   onChange={handleFieldChange}
//                 />
//               </Grid>

//               {/* ── DIMENSIONS ───────────────────────────────────────────────── */}
//               <Grid item xs={12} sm={6}>
//                 <TextField
//                   label="Dimensions"
//                   name="dimensions"
//                   fullWidth
//                   value={selectedProduct.dimensions || ''}
//                   onChange={handleFieldChange}
//                 />
//               </Grid>

//               {/* ── HSN CODE ─────────────────────────────────────────────────── */}
//               <Grid item xs={12} sm={6}>
//                 <TextField
//                   label="HSN Code"
//                   name="hsn_code"
//                   fullWidth
//                   value={selectedProduct.hsn_code || ''}
//                   onChange={handleFieldChange}
//                 />
//               </Grid>

//               {/* ── BARCODE ──────────────────────────────────────────────────── */}
//               <Grid item xs={12}>
//                 <TextField
//                   label="Barcode"
//                   name="barcode"
//                   fullWidth
//                   value={selectedProduct.barcode || ''}
//                   onChange={handleFieldChange}
//                 />
//               </Grid>

//               {/* Barcode and QR Code Preview */}
//               <Grid item xs={12}>
//                 <Box sx={{ mt: 2 }}>
//                   <Typography variant="subtitle1" gutterBottom>
//                     Barcode & QR Code:
//                   </Typography>
//                   <Box sx={{ 
//                     display: 'flex', 
//                     justifyContent: 'space-around',
//                     alignItems: 'center',
//                     gap: 2
//                   }}>
//                     {/* Barcode Preview */}
//                     <Box sx={{ textAlign: 'center' }}>
//                       <Typography variant="subtitle2" gutterBottom>
//                         Barcode
//                       </Typography>
//                       <svg ref={barcodeRef} style={{ maxWidth: '100%', height: '60px' }} />
//                       <Button 
//                         variant="outlined" 
//                         onClick={downloadBarcode}
//                         sx={{ mt: 1 }}
//                         size="small"
//                       >
//                         Download
//                       </Button>
//                     </Box>

//                     {/* QR Code Preview */}
//                     <Box sx={{ textAlign: 'center' }} ref={qrCodeRef}>
//                       <Typography variant="subtitle2" gutterBottom>
//                         QR Code
//                       </Typography>
//                       <QRCodeCanvas 
//                         value={selectedProduct.barcode || ''} 
//                         size={100} 
//                       />
//                       <Button 
//                         variant="outlined" 
//                         onClick={downloadQR}
//                         sx={{ mt: 1 }}
//                         size="small"
//                       >
//                         Download
//                       </Button>
//                     </Box>
//                   </Box>
//                 </Box>
//               </Grid>

//               {/* ── UNIT ─────────────────────────────────────────────────────── */}
//               <Grid item xs={12} sm={6}>
//                 <TextField
//                   label="Unit"
//                   name="unit"
//                   fullWidth
//                   value={selectedProduct.unit || ''}
//                   onChange={handleFieldChange}
//                 />
//               </Grid>

//               {/* ── STATUS ───────────────────────────────────────────────────── */}
//               <Grid item xs={12} sm={6}>
//                 <TextField
//                   label="Status"
//                   name="status"
//                   fullWidth
//                   value={selectedProduct.status || ''}
//                   onChange={handleFieldChange}
//                 />
//               </Grid>

//               {/* ── CONDITION ────────────────────────────────────────────────── */}
//               <Grid item xs={12}>
//                 <TextField
//                   label="Condition"
//                   name="condition"
//                   fullWidth
//                   value={selectedProduct.condition || ''}
//                   onChange={handleFieldChange}
//                 />
//               </Grid>
//             </Grid>

//             <Box sx={{ mt: 3, display: 'flex', justifyContent: 'flex-end' }}>
//               <Button
//                 onClick={() => {
//                   setSidebarOpen(false);
//                   setSelectedProduct(null);
//                   setNewImageFile(null);
//                 }}
//                 sx={{ mr: 1 }}
//               >
//                 Cancel
//               </Button>
//               <Button
//                 variant="contained"
//                 onClick={handleUpdate}
//                 disabled={isSaving}
//               >
//                 {isSaving ? 'Saving...' : 'Save'}
//               </Button>
//             </Box>
//           </Stack>
//         )}
//       </Drawer>
//     </Box>
//   );
// };

// export default ProductList;
import React, { useState, useEffect, useRef } from 'react';
import axios from 'axios';
import {
  Box,
  Typography,
  Table,
  TableBody,
  TableCell,
  TableContainer,
  TableHead,
  TableRow,
  Paper,
  Avatar,
  IconButton,
  Drawer,
  TextField,
  Button,
  Divider,
  Stack,
  Grid,
  InputAdornment
} from '@mui/material';
import { Delete as DeleteIcon, Edit as EditIcon, Upload as UploadIcon, Search as SearchIcon } from '@mui/icons-material';
import JsBarcode from 'jsbarcode';
import { QRCodeCanvas } from 'qrcode.react';

const ProductList = ({ products, setProducts }) => {
  // State for search functionality
  const [searchTerm, setSearchTerm] = useState('');
  const [filteredProducts, setFilteredProducts] = useState(products);
  
  // ── State for sidebar (Drawer) ──────────────────────────────────────────────
  const [isSidebarOpen, setSidebarOpen] = useState(false);
  const [selectedProduct, setSelectedProduct] = useState(null);
  const [isSaving, setIsSaving] = useState(false);
  const barcodeRef = useRef();
  const qrCodeRef = useRef();

  // ── Track newly chosen file (if any) ───────────────────────────────────────
  const [newImageFile, setNewImageFile] = useState(null);
  const [previewUrl, setPreviewUrl] = useState('');

  // Update filtered products when search term or products change
  useEffect(() => {
    const filtered = products.filter(product => {
      const searchLower = searchTerm.toLowerCase();
      return (
        (product.name && product.name.toLowerCase().includes(searchLower)) ||
        (product.description && product.description.toLowerCase().includes(searchLower)) ||
        (product.category && product.category.toLowerCase().includes(searchLower)) ||
        (product.sku && product.sku.toLowerCase().includes(searchLower)) ||
        (product.barcode && product.barcode.toLowerCase().includes(searchLower))
      );
    });
    setFilteredProducts(filtered);
  }, [searchTerm, products]);

  // Generate barcode whenever selected product changes
  useEffect(() => {
    if (selectedProduct && barcodeRef.current) {
      const barcodeValue = selectedProduct.barcode || 
                         `PROD-${selectedProduct.id || ''}-${selectedProduct.sku || ''}`;
      
      try {
        while (barcodeRef.current.firstChild) {
          barcodeRef.current.removeChild(barcodeRef.current.firstChild);
        }
        
        JsBarcode(barcodeRef.current, barcodeValue, {
          format: "CODE128",
          displayValue: true,
          width: 2,
          height: 60,
        });
        
        if (!selectedProduct.barcode) {
          setSelectedProduct(prev => ({
            ...prev,
            barcode: barcodeValue,
          }));
        }
      } catch (error) {
        console.error("Barcode generation error:", error);
      }
    }
  }, [selectedProduct]);

  // Handle image previews
  useEffect(() => {
    if (selectedProduct) {
      if (newImageFile) {
        setPreviewUrl(URL.createObjectURL(newImageFile));
      } else {
        setPreviewUrl(
          selectedProduct.image
            ? `/storage/${selectedProduct.image}`
            : '/placeholder-product.png'
        );
      }
    } else {
      setPreviewUrl('');
    }
  }, [selectedProduct, newImageFile]);

  // ── User clicks "Edit" icon ──────────────────────────────────────────────────
  const handleEditClick = (product, index) => {
    setSelectedProduct({ ...product, _idx: index });
    setNewImageFile(null);
    setSidebarOpen(true);
  };

  const handleDelete = async (id, index) => {
    const confirmDelete = window.confirm("Are you sure you want to delete this product?");
    if (!confirmDelete) return;

    try {
      await axios.delete(`/api/v1/products/delete/${id}`);
      const updated = [...products];
      updated.splice(index, 1);
      setProducts(updated);
    } catch (error) {
      console.error("Delete failed:", error);
    }
  };

  // ── Track changes to text fields ─────────────────────────────────────────────
  const handleFieldChange = (e) => {
    const { name, value } = e.target;
    setSelectedProduct((prev) => ({
      ...prev,
      [name]: value,
    }));
  };

  // ── Handle new image selection ───────────────────────────────────────────────
  const handleImageChange = (e) => {
    if (e.target.files && e.target.files[0]) {
      const file = e.target.files[0];
      setNewImageFile(file);
    }
  };

  const downloadBarcode = () => {
    if (!barcodeRef.current) return;
    
    const svg = barcodeRef.current;
    const serializer = new XMLSerializer();
    const source = serializer.serializeToString(svg);
    const blob = new Blob([source], { type: "image/svg+xml;charset=utf-8" });
    const url = URL.createObjectURL(blob);

    const link = document.createElement("a");
    link.href = url;
    link.download = `barcode-${selectedProduct.sku || selectedProduct.id || 'product'}.svg`;
    link.click();
  };

  const downloadQR = () => {
    const canvas = qrCodeRef.current?.querySelector("canvas");
    if (canvas) {
      const url = canvas.toDataURL("image/png");
      const link = document.createElement("a");
      link.href = url;
      link.download = `qrcode-${selectedProduct.sku || selectedProduct.id || 'product'}.png`;
      link.click();
    }
  };

  // ── When "Save" is clicked ───────────────────────────────────────────────────
  const handleUpdate = async () => {
    if (!selectedProduct) return;
    setIsSaving(true);

    try {
      const formData = new FormData();
      const { id, _idx, ...fields } = selectedProduct;

      Object.entries(fields).forEach(([key, val]) => {
        formData.append(key, val ?? '');
      });

      if (newImageFile) {
        formData.append('image', newImageFile);
      }

      formData.append('_method', 'PUT');

      const response = await axios.post(`/api/v1/products/update/${id}`, formData, {
        headers: {
          'Content-Type': 'multipart/form-data',
        },
      });

      const updatedProducts = [...products];
      updatedProducts[_idx] = response.data;
      setProducts(updatedProducts);

      setSidebarOpen(false);
      setSelectedProduct(null);
      setNewImageFile(null);
    } catch (err) {
      console.error('Failed to update product:', err);
    } finally {
      setIsSaving(false);
    }
  };

  return (
    <Box sx={{ mt: 4 }}>
      {/* ──────────── BEAUTIFUL SEARCH BAR ───────────────────────────── */}
      <Box sx={{ mb: 3, display: 'flex', justifyContent: 'space-between', alignItems: 'center' }}>
        <Typography variant="h5" gutterBottom>
          Product List
        </Typography>
        
        <TextField
          placeholder="Search products..."
          variant="outlined"
          size="small"
          value={searchTerm}
          onChange={(e) => setSearchTerm(e.target.value)}
          sx={{ 
            width: '300px',
            '& .MuiOutlinedInput-root': {
              borderRadius: '50px',
              boxShadow: '0 2px 6px rgba(0,0,0,0.1)',
              transition: 'all 0.3s ease',
              '&:hover': {
                boxShadow: '0 4px 8px rgba(0,0,0,0.15)',
              },
              '&.Mui-focused': {
                boxShadow: '0 4px 12px rgba(0,0,0,0.2)',
              }
            }
          }}
          InputProps={{
            startAdornment: (
              <InputAdornment position="start">
                <SearchIcon color="action" />
              </InputAdornment>
            ),
          }}
        />
      </Box>

      {filteredProducts.length === 0 ? (
        <Box sx={{ 
          p: 4, 
          textAlign: 'center',
          border: '1px dashed #ddd',
          borderRadius: 2,
          backgroundColor: '#fafafa'
        }}>
          <Typography variant="body1" color="textSecondary">
            {searchTerm ? 
              `No products found for "${searchTerm}"` : 
              'No products added yet.'
            }
          </Typography>
          {searchTerm && (
            <Button 
              variant="text" 
              color="primary" 
              sx={{ mt: 1 }}
              onClick={() => setSearchTerm('')}
            >
              Clear search
            </Button>
          )}
        </Box>
      ) : (
        <TableContainer 
          component={Paper} 
          elevation={2}
          sx={{ borderRadius: 2, overflow: 'hidden' }}
        >
          <Table>
            <TableHead sx={{ backgroundColor: '#f5f5f5' }}>
              <TableRow>
                <TableCell>Image</TableCell>
                <TableCell>Name</TableCell>
                <TableCell>Price</TableCell>
                <TableCell>Stock</TableCell>
                <TableCell>Category</TableCell>
                <TableCell align="center">Actions</TableCell>
              </TableRow>
            </TableHead>

            <TableBody>
              {filteredProducts.map((product, index) => (
                <TableRow 
                  key={product.id || index}
                  sx={{ 
                    '&:hover': { backgroundColor: '#f9f9f9' },
                    '&:last-child td': { borderBottom: 0 }
                  }}
                >
                  <TableCell>
                    <Avatar
                      src={
                        product.image
                          ? `/storage/${product.image}`
                          : '/placeholder-product.png'
                      }
                      variant="rounded"
                      sx={{ width: 56, height: 56 }}
                    />
                  </TableCell>

                  <TableCell>
                    <Typography fontWeight="bold">
                      {product.name}
                    </Typography>
                    <Typography variant="body2" color="text.secondary">
                      {(product.description || '').substring(0, 50)}...
                    </Typography>
                  </TableCell>

                  <TableCell>${product.base_price}</TableCell>
                  <TableCell>{product.stock}</TableCell>
                  <TableCell>{product.category}</TableCell>

                  <TableCell align="center">
                    <IconButton
                      color="primary"
                      onClick={() => handleEditClick(product, index)}
                      sx={{ mr: 1 }}
                    >
                      <EditIcon />
                    </IconButton>
                    <IconButton
                      color="error"
                      onClick={() => handleDelete(product.id, index)}
                    >
                      <DeleteIcon />
                    </IconButton>
                  </TableCell>
                </TableRow>
              ))}
            </TableBody>
          </Table>
        </TableContainer>
      )}

      {/* ─────────────── Beautiful Edit Drawer ───────────────────────────────── */}
      <Drawer
        anchor="right"
        open={isSidebarOpen}
        onClose={() => {
          setSidebarOpen(false);
          setSelectedProduct(null);
          setNewImageFile(null);
        }}
        PaperProps={{
          sx: {
            width: { xs: 320, sm: 600 },
            p: 3,
            borderTopLeftRadius: 16,
            borderBottomLeftRadius: 16,
            bgcolor: 'background.paper',
            boxShadow: 6,
          },
        }}
      >
        <Typography variant="h6" gutterBottom>
          Edit Product
        </Typography>
        <Divider sx={{ mb: 2 }} />

        {selectedProduct && (
          <Stack spacing={2}>
            {/* ── IMAGE PREVIEW & UPLOAD ─────────────────────────────────── */}
            <Box
              sx={{
                display: 'flex',
                flexDirection: 'column',
                alignItems: 'center',
                gap: 1,
              }}
            >
              <Avatar
                src={previewUrl}
                variant="rounded"
                sx={{ width: 96, height: 96 }}
              />
              <Button
                component="label"
                variant="outlined"
                startIcon={<UploadIcon />}
                sx={{ textTransform: 'none' }}
              >
                Re-upload Image
                <input
                  type="file"
                  accept="image/*"
                  hidden
                  onChange={handleImageChange}
                />
              </Button>
              <Typography variant="caption" color="text.secondary">
                {newImageFile ? newImageFile.name : 'Current image preview'}
              </Typography>
            </Box>

            <Divider />

            <Grid container spacing={2}>
              {/* ── NAME ─────────────────────────────────────────────────────── */}
              <Grid item xs={12}>
                <TextField
                  label="Name"
                  name="name"
                  fullWidth
                  value={selectedProduct.name}
                  onChange={handleFieldChange}
                  required
                />
              </Grid>

              {/* ── DESCRIPTION ──────────────────────────────────────────────── */}
              <Grid item xs={12}>
                <TextField
                  label="Description"
                  name="description"
                  fullWidth
                  multiline
                  minRows={2}
                  value={selectedProduct.description || ''}
                  onChange={handleFieldChange}
                />
              </Grid>

              {/* ── PRICE ────────────────────────────────────────────────────── */}
              <Grid item xs={12} sm={6}>
                <TextField
                  label="Price"
                  name="base_price"
                  type="number"
                  fullWidth
                  value={selectedProduct.base_price}
                  onChange={handleFieldChange}
                  required
                />
              </Grid>

              {/* ── CATEGORY ─────────────────────────────────────────────────── */}
              <Grid item xs={12} sm={6}>
                <TextField
                  label="Category"
                  name="category"
                  fullWidth
                  value={selectedProduct.category || ''}
                  onChange={handleFieldChange}
                />
              </Grid>

              {/* ── STOCK ────────────────────────────────────────────────────── */}
              <Grid item xs={12} sm={6}>
                <TextField
                  label="Stock"
                  name="stock"
                  type="number"
                  fullWidth
                  value={selectedProduct.stock}
                  onChange={handleFieldChange}
                  required
                />
              </Grid>

              {/* ── SKU ──────────────────────────────────────────────────────── */}
              <Grid item xs={12} sm={6}>
                <TextField
                  label="SKU"
                  name="sku"
                  fullWidth
                  value={selectedProduct.sku || ''}
                  onChange={handleFieldChange}
                />
              </Grid>

              {/* ── BARCODE ──────────────────────────────────────────────────── */}
              <Grid item xs={12}>
                <TextField
                  label="Barcode"
                  name="barcode"
                  fullWidth
                  value={selectedProduct.barcode || ''}
                  onChange={handleFieldChange}
                />
              </Grid>

              {/* Barcode and QR Code Preview */}
              <Grid item xs={12}>
                <Box sx={{ mt: 2 }}>
                  <Typography variant="subtitle1" gutterBottom>
                    Barcode & QR Code:
                  </Typography>
                  <Box sx={{ 
                    display: 'flex', 
                    justifyContent: 'space-around',
                    alignItems: 'center',
                    gap: 2
                  }}>
                    {/* Barcode Preview */}
                    <Box sx={{ textAlign: 'center' }}>
                      <Typography variant="subtitle2" gutterBottom>
                        Barcode
                      </Typography>
                      <svg ref={barcodeRef} style={{ maxWidth: '100%', height: '60px' }} />
                      <Button 
                        variant="outlined" 
                        onClick={downloadBarcode}
                        sx={{ mt: 1 }}
                        size="small"
                      >
                        Download
                      </Button>
                    </Box>

                    {/* QR Code Preview */}
                    <Box sx={{ textAlign: 'center' }} ref={qrCodeRef}>
                      <Typography variant="subtitle2" gutterBottom>
                        QR Code
                      </Typography>
                      <QRCodeCanvas 
                        value={selectedProduct.barcode || ''} 
                        size={100} 
                      />
                      <Button 
                        variant="outlined" 
                        onClick={downloadQR}
                        sx={{ mt: 1 }}
                        size="small"
                      >
                        Download
                      </Button>
                    </Box>
                  </Box>
                </Box>
              </Grid>
            </Grid>

            <Box sx={{ mt: 3, display: 'flex', justifyContent: 'flex-end' }}>
              <Button
                onClick={() => {
                  setSidebarOpen(false);
                  setSelectedProduct(null);
                  setNewImageFile(null);
                }}
                sx={{ mr: 1 }}
              >
                Cancel
              </Button>
              <Button
                variant="contained"
                onClick={handleUpdate}
                disabled={isSaving}
              >
                {isSaving ? 'Saving...' : 'Save'}
              </Button>
            </Box>
          </Stack>
        )}
      </Drawer>
    </Box>
  );
};

export default ProductList;