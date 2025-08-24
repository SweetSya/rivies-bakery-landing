# Rivies Bakery - Landing Page (E-Commerce)

Tech Stack:  
- **Backend**: Laravel  
- **Frontend**: Inertia.js + Vue 3  
- **Styling**: TailwindCSS  

---

## 🎯 Goals
- Showcase Rivies Bakery products online.
- Allow customers to browse, search, and purchase bakery items.
- Provide cart, checkout, and payment support.
- Create a responsive, modern, and user-friendly UI.

---

## 📌 Features
- **Home Page**
  - Hero section with bakery branding
  - Highlighted products / categories
  - Promotional banners
- **Product Catalog**
  - Category-based filtering
  - Product details page (name, description, price, image, stock)
- **Cart & Checkout**
  - Add to cart / remove from cart
  - Quantity updates
  - Checkout form (customer details, shipping, payment option)
- **Authentication**
  - Register, Login, Logout
  - Order history for users
- **Payment Integration**
  - Midtrans / Stripe (TBD)

---

## 🏗️ Pages
- `/` → Landing page
- `/products` → Product catalog
- `/products/{slug}` → Single product detail
- `/cart` → Shopping cart
- `/checkout` → Checkout
- `/orders` → User order history
- `/auth/login` → Login
- `/auth/register` → Register

---

## 🎨 UI/UX
- TailwindCSS for styling
- Mobile-first responsive design
- Inertia.js for SPA-like navigation
- Vue 3 components for interactivity (Cart, Modal, Search, etc.)

---