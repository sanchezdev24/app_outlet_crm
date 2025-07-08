import Dashboard from './components/Dashboard.vue'
import Login from './components/Auth/Login.vue'
import CustomerList from './components/Customer/CustomerList.vue'
import CustomerForm from './components/Customer/CustomerForm.vue'
import ProductList from './components/Product/ProductList.vue'
import ProductForm from './components/Product/ProductForm.vue'
import SaleList from './components/Sale/SaleList.vue'
import SaleForm from './components/Sale/SaleForm.vue'

const routes = [
    {
        path: '/login',
        name: 'login',
        component: Login,
        meta: { requiresGuest: true }
    },
    {
        path: '/',
        name: 'dashboard',
        component: Dashboard,
        meta: { requiresAuth: true }
    },
    {
        path: '/customers',
        name: 'customers',
        component: CustomerList,
        meta: { requiresAuth: true }
    },
    {
        path: '/customers/create',
        name: 'customer-create',
        component: CustomerForm,
        meta: { requiresAuth: true }
    },
    {
        path: '/customers/:id/edit',
        name: 'customer-edit',
        component: CustomerForm,
        meta: { requiresAuth: true }
    },
    {
        path: '/products',
        name: 'products',
        component: ProductList,
        meta: { requiresAuth: true }
    },
    {
        path: '/products/create',
        name: 'product-create',
        component: ProductForm,
        meta: { requiresAuth: true }
    },
    {
        path: '/products/:id/edit',
        name: 'product-edit',
        component: ProductForm,
        meta: { requiresAuth: true }
    },
    {
        path: '/sales',
        name: 'sales',
        component: SaleList,
        meta: { requiresAuth: true }
    },
    {
        path: '/sales/create',
        name: 'sale-create',
        component: SaleForm,
        meta: { requiresAuth: true }
    }
]

export default routes;