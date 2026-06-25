const { createApp } = Vue;
const { createRouter, createWebHashHistory } = VueRouter;

// Pastikan ini ngarah ke port server CI4 lu
const apiUrl = 'http://localhost:8080';
// ===
// IMPLEMENTASI AXIOS INTERCEPTORS (Penyuntik Token Otomatis)
// ======
axios.interceptors.request.use(
    (config) => {
        // Ambil token dari local storage browser
        const token = localStorage.getItem('userToken');
        // Jika token tersedia, masukkan ke dalam HTTP Header Authorization Bearer
        if (token) {
            config.headers['Authorization'] = 'Bearer ' + token;
        }
        return config;
    },
    (error) => {
        return Promise.reject(error);
    }
);

// Tangkap secara global jika server merespon dengan error 401 (Unauthorized)
axios.interceptors.response.use(
    (response) => {
        return response;
    },
    (error) => {
        if (error.response && error.response.status === 401) {
            alert('Sesi Anda telah berakhir atau Token tidak sah. Silakan login kembali.');
            localStorage.clear(); // Bersihkan local storage
            window.location.href = '#/login'; // Tendang paksa ke halaman login
            window.location.reload();
        }
        return Promise.reject(error);
    }
);

// 1. Definisikan mapping rute URL ke Komponen beserta properti Meta-Auth [cite: 1961]
const routes = [
    { path: '/', component: Home },
    { path: '/login', component: Login },
    { 
        path: '/artikel', 
        component: Artikel,
        meta: { requiresAuth: true } // Hanya boleh diakses jika user sudah login [cite: 1968]
    },
    { 
        path: '/about', 
        component: About,
        meta: { requiresAuth: true } // Mengunci halaman About sesuai tugas modul 
    }
];

const router = createRouter({
    history: createWebHashHistory(),
    routes
});

// 2. Implementasi Navigation Guards (Pencegat Akses Rute) [cite: 1975]
// 2. Implementasi Navigation Guards (Pencegat Akses Rute)
router.beforeEach((to, from, next) => {
    const isAuthenticated = localStorage.getItem('isLoggedIn') === 'true';

    // Jika rute tujuan membutuhkan autentikasi dan user belum login
    if (to.matched.some(record => record.meta.requiresAuth) && !isAuthenticated) {
        
        // Ganti alert bawaan pake SweetAlert2 Toast
        Swal.fire({
            toast: true,
            position: 'top-end',
            icon: 'error',
            title: 'Akses Ditolak!',
            text: 'Silakan login terlebih dahulu.',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true
        });

        next('/login'); // Belokkan paksa ke halaman login
    } else {
        next(); // Izinkan akses menuju rute tujuan
    }
});

// 3. Inisialisasi Root Instance dengan State Navigasi Global [cite: 1989]
const app = createApp({
    data() {
        return {
            isLoggedIn: false
        }
    },
    mounted() {
        // Cek status login saat aplikasi pertama kali dimuat oleh browser [cite: 1997]
        this.isLoggedIn = localStorage.getItem('isLoggedIn') === 'true';
    },
    methods: {
        logout() {
            if (confirm('Apakah Anda yakin ingin keluar aplikasi?')) {
                localStorage.removeItem('isLoggedIn');
                localStorage.removeItem('userToken');
                this.isLoggedIn = false;
                this.$router.push('/');
            }
        }
    }
});

app.use(router);
app.mount('#app');