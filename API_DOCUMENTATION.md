# API Documentation - Sistem Pertunjukan Seni

Base URL: `http://localhost:8000/api/v1`

## Authentication

API menggunakan Laravel Sanctum untuk authentication. Untuk mendapatkan token:

1. **Register**: POST `/register` (dari Laravel Breeze)
2. **Login**: POST `/login` (dari Laravel Breeze)
3. Gunakan token di header: `Authorization: Bearer {token}`

---

## Public Endpoints (No Auth Required)

### Pertunjukan (Shows)

#### GET `/pertunjukans`

List semua pertunjukan aktif dengan pagination, search, dan filter.

**Query Parameters:**

-   `search` - Search by judul atau lokasi
-   `seniman_id` - Filter by seniman
-   `min_harga`, `max_harga` - Filter by price range
-   `tanggal_dari`, `tanggal_sampai` - Filter by date range
-   `sort_by` - Sort field (default: tanggal_pertunjukan)
-   `sort_order` - asc/desc (default: asc)
-   `per_page` - Items per page (default: 12)

**Response:**

```json
{
  "data": [
    {
      "id": 1,
      "judul": "Wayang Kulit Ramayana",
      "deskripsi": "...",
      "tanggal_pertunjukan": "2025-01-15 19:00:00",
      "lokasi": "Gedung Kesenian Jakarta",
      "harga": "150000.00",
      "kuota": 200,
      "kuota_tersisa": 150,
      "gambar": "pertunjukans/image.jpg",
      "status": "active",
      "seniman": {
        "id": 1,
        "nama": "Ki Dalang Asep",
        "kategori": "wayang"
      }
    }
  ],
  "links": {...},
  "meta": {...}
}
```

#### GET `/pertunjukans/{id}`

Detail pertunjukan

---

### Seniman (Artists)

#### GET `/senimans`

List seniman dengan search dan filter.

**Query Parameters:**

-   `search` - Search by nama
-   `kategori` - Filter by kategori
-   `per_page` - Items per page (default: 12)

#### GET `/senimans/{id}`

Profil seniman dengan daftar pertunjukannya

---

### Berita (News)

#### GET `/beritas`

List berita yang sudah published.

**Query Parameters:**

-   `search` - Search by judul atau konten
-   `kategori` - Filter by kategori
-   `per_page` - Items per page (default: 10)

#### GET `/beritas/{id}`

Detail berita

---

## Protected Endpoints (Auth Required)

### User Info

#### GET `/user`

Get authenticated user info

---

### Booking

#### GET `/bookings`

List booking history user yang login

#### POST `/bookings`

Create new booking

**Request Body:**

```json
{
    "pertunjukan_id": 1,
    "jumlah_tiket": 2
}
```

**Response:**

```json
{
  "message": "Booking berhasil dibuat",
  "booking": {
    "id": 1,
    "kode_booking": "BK-ABC12345",
    "jumlah_tiket": 2,
    "total_harga": "300000.00",
    "status": "pending",
    "pertunjukan": {...}
  }
}
```

#### GET `/bookings/{id}`

Detail booking

---

### Transaction

#### GET `/transactions`

List transaction history

#### POST `/transactions`

Upload bukti pembayaran

**Request Body (multipart/form-data):**

-   `booking_id` - ID booking
-   `metode_pembayaran` - transfer/cash/e-wallet
-   `bukti_pembayaran` - Image file (jpeg, png, jpg, max 2MB)

#### GET `/transactions/{id}`

Detail transaction

---

### Wishlist

#### GET `/wishlists`

Get user's wishlist

#### POST `/wishlists`

Add to wishlist

**Request Body:**

```json
{
    "pertunjukan_id": 1
}
```

#### DELETE `/wishlists/{id}`

Remove from wishlist

---

## Admin Endpoints (Auth + Admin Role Required)

Prefix: `/admin`

### Dashboard

#### GET `/admin/dashboard/stats`

Get dashboard statistics (total pertunjukans, bookings, revenue, dll)

---

### Pertunjukan Management

#### GET `/admin/pertunjukans`

List all pertunjukans (active & inactive)

#### POST `/admin/pertunjukans`

Create new pertunjukan

**Request Body (multipart/form-data):**

-   `judul` - required
-   `deskripsi` - required
-   `tanggal_pertunjukan` - required (date)
-   `lokasi` - required
-   `harga` - required (numeric)
-   `kuota` - required (integer)
-   `seniman_id` - required
-   `gambar` - optional (image file)
-   `status` - optional (active/inactive)

#### GET `/admin/pertunjukans/{id}`

Get pertunjukan detail

#### PUT/PATCH `/admin/pertunjukans/{id}`

Update pertunjukan

#### DELETE `/admin/pertunjukans/{id}`

Delete pertunjukan

---

### Seniman Management

Full CRUD endpoints sama seperti Pertunjukan:

-   GET `/admin/senimans`
-   POST `/admin/senimans`
-   GET `/admin/senimans/{id}`
-   PUT/PATCH `/admin/senimans/{id}`
-   DELETE `/admin/senimans/{id}`

---

### Berita Management

Full CRUD endpoints sama seperti Pertunjukan:

-   GET `/admin/beritas`
-   POST `/admin/beritas`
-   GET `/admin/beritas/{id}`
-   PUT/PATCH `/admin/beritas/{id}`
-   DELETE `/admin/beritas/{id}`

---

### Booking Management

#### GET `/admin/bookings`

List all bookings dengan filter

**Query Parameters:**

-   `status` - Filter by status
-   `pertunjukan_id` - Filter by pertunjukan
-   `search` - Search by kode_booking atau user name

#### GET `/admin/bookings/{id}`

Get booking detail

#### PATCH `/admin/bookings/{id}/status`

Update booking status

**Request Body:**

```json
{
    "status": "confirmed"
}
```

#### DELETE `/admin/bookings/{id}`

Delete booking (akan restore kuota)

---

### Transaction Management

#### GET `/admin/transactions`

List all transactions

**Query Parameters:**

-   `status` - Filter by status
-   `metode_pembayaran` - Filter by payment method

#### GET `/admin/transactions/{id}`

Get transaction detail

#### PATCH `/admin/transactions/{id}/status`

Approve/reject payment

**Request Body:**

```json
{
    "status": "paid"
}
```

---

## Error Responses

```json
{
    "message": "Error message here",
    "errors": {
        "field": ["Validation error message"]
    }
}
```

## Status Codes

-   `200` - Success
-   `201` - Created
-   `400` - Bad Request
-   `401` - Unauthenticated
-   `403` - Unauthorized (not admin)
-   `404` - Not Found
-   `422` - Validation Error
-   `500` - Server Error
