<template>
    <div class="wrap-index pt-3">
        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">{{ editMode ? 'Edit' : 'Tambah' }} Data</h5>
                    <button type="button" class="close" @click="closeModal()" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form>
                        <div class="form-group">
                            <label for="user">Pilih User</label>
                            <select v-model="book.user_id" name="user" id="user" :class="[ errors.user_id ? 'is-invalid' : '' ]" class="form-control">
                                <option v-for="data in users" :value="data.id">{{ data.nama }}</option>
                            </select>
                            <div class="clearfix py-1">
                                 <small v-if="errors.user_id" :class="[ errors.user_id ? 'float-left' : '' ]" class="text-danger">{{ errors.user_id }}</small>
                                <small :class="[ errors.user_id ? 'float-right' : '' ]">User tidak ada di list ? <a href="#" @click="toUsers()">daftar</a></small>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="judul">Judul Buku</label>
                            <input v-model="book.judul" type="text" class="form-control" :class="[ errors.judul ? 'is-invalid' : '' ]" id="judul">
                            <div v-if="errors.judul" class="invalid-feedback">
                                {{ errors.judul }}
                            </div>
                        </div>
                        <div class="row">
                            <div class="form-group col-4">
                                <label for="jumlah_halaman">Jumlah Halaman</label>
                                <input v-model="book.jumlah_halaman" type="number" class="form-control" :class="[ errors.jumlah_halaman ? 'is-invalid' : '' ]" id="jumlah_halaman">
                                <div v-if="errors.jumlah_halaman" class="invalid-feedback">
                                    {{ errors.jumlah_halaman }}
                                </div>
                            </div>
                            <div class="form-group col-8">
                                <label for="penerbit">Penerbit</label>
                                <input v-model="book.penerbit" type="text" class="form-control" :class="[ errors.penerbit ? 'is-invalid' : '' ]" id="penerbit">
                                <div v-if="errors.penerbit" class="invalid-feedback">
                                    {{ errors.penerbit }}
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-sm btn-secondary" @click="closeModal()">Tutup</button>
                    <button @click="editMode ? update() : store()" type="button" class="btn btn-sm btn-outline-primary">Simpan</button>
                </div>
                </div>
            </div>
        </div>
        <!-- End Modal -->

        <h3>Buku</h3>
        <h6 class="text-muted">Lihat semua data buku disini.</h6>

        <div class="row mt-5">
            <div class="col-6">
                <h4 class="text-dark float-left">Semua Data ({{ count_books }})</h4>
            </div>
            <div class="col-6">
                <button type="button" @click="openModal('create')" class="btn btn-outline-primary float-right btn-sm">Tambah Data</button>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <table v-if="books.length > 0" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Judul</th>
                            <th>Halaman</th>
                            <th>Penerbit</th>
                            <th>Diinput oleh</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(data, key) in books">
                            <td>{{ ++key }}</td>
                            <td>{{ data.judul }}</td>
                            <td>{{ data.jumlah_halaman }}</td>
                            <td>{{ data.penerbit }}</td>
                            <td>{{ data.users.nama }}</td>
                            <td>
                                <button type="button" @click="openModal('edit', data)" class="btn btn-outline-warning btn-sm">Edit</button>
                                <button type="button" @click="destroy(data)" class="btn btn-outline-danger btn-sm">Hapus</button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <h5 v-else class="text-center">Belum ada data :(</h5>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                editMode: false,
                errors: {
                    user_id: '',
                    judul: '',
                    jumlah_halaman: '',
                    penerbit: ''
                },
                book: {
                    id: '',
                    user_id: '',
                    judul: '',
                    jumlah_halaman: '',
                    penerbit: ''
                },
                books: {},
                users: {},
                count_books: 0
            }
        },
        methods: {
            closeModal() {
                this.errors.user_id = '';
                this.errors.judul = '';
                this.errors.jumlah_halaman = '';
                this.errors.penerbit = '';

                $('#exampleModal').modal('hide');
            },
            toUsers() {
                $('#exampleModal').modal('hide');

                this.$router.push('/users');
            },
            validate() {
                if(!this.book.user_id) {
                    this.errors.user_id = 'User belum dipilih';
                }
                if(!this.book.judul) {
                    this.errors.judul = 'Judul harus diisi';
                }
                if(!this.book.jumlah_halaman) {
                    this.errors.jumlah_halaman = 'Judul harus diisi';
                }
                if(!this.book.penerbit) {
                    this.errors.penerbit = 'Judul harus diisi';
                }

                if(this.book.user_id && this.book.judul && this.book.jumlah_halaman && this.book.penerbit) {
                    return true;
                }
            },
            openModal(type, data = null) {
                if(type === 'create') {
                    this.editMode = false;
                    this.book.id = '';
                    this.book.user_id = '';
                    this.book.judul = '';
                    this.book.jumlah_halaman = '';
                    this.book.penerbit = '';
                } else {
                    this.editMode = true;
                    this.book.id = data.id;
                    this.book.user_id = data.user_id;
                    this.book.judul = data.judul;
                    this.book.jumlah_halaman = data.jumlah_halaman;
                    this.book.penerbit = data.penerbit;
                }

                $('#exampleModal').modal('show');
            },
            getAllBooks() {
                this.$Progress.start();
                axios.get('/api/books')
                .then(({ data }) => {
                    this.getAllUsers()
                    this.$Progress.finish();
                    this.books = data.books;
                    this.count_books = data.count;
                    console.log(data.books)
                }).catch(() => {
                    this.$Progress.fail();
                })
            },
            getAllUsers() {
                axios.get('/api/users')
                .then(({ data }) => {
                    this.users = data.users;
                }).catch(() => {

                })
            },
            store() {
                if(this.validate()) {
                    this.$Progress.start();
                    axios.post('/api/books', this.book)
                    .then((res) => {
                        this.getAllBooks();
                        this.book.id = '';
                        this.book.user_id = '';
                        this.book.judul = '';
                        this.book.jumlah_halaman = '';
                        this.book.penerbit = '';
                        $('#exampleModal').modal('hide');
                        this.$Progress.finish();
                        Swal.fire({
                            title: 'Success!',
                            text: res.data.message,
                            type: 'success',
                            timer: 3000,
                            showConfirmButton: false
                        })
                    }).catch((error) => {
                        this.$Progress.fail();
                        Swal.fire({
                            title: 'Error!',
                            text: error.response.data.message,
                            type: 'error',
                            timer: 3000,
                            showConfirmButton: false
                        })
                    })
                }
            },
            update() {
                if(this.validate()) {
                    this.$Progress.start();
                    axios.put('/api/books/'+ this.book.id, this.book,)
                    .then((res) => {
                        this.getAllBooks();
                        $('#exampleModal').modal('hide');
                        this.$Progress.finish();
                        Swal.fire({
                            title: 'Success!',
                            text: res.data.message,
                            type: 'success',
                            timer: 3000,
                            showConfirmButton: false
                        })
                    }).catch((error) => {
                        this.$Progress.fail();
                        Swal.fire({
                            title: 'Error!',
                            text: error.response.data.message,
                            type: 'error',
                            timer: 3000,
                            showConfirmButton: false
                        })
                    })
                }
            },
            destroy(data) {
                Swal.fire({
                    title: 'Anda yakin?',
                    text: data.judul +" Akan dihapus!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Kembali'
                    }).then((result) => {
                    if (result.value) {
                        this.$Progress.start();
                        axios.delete('/api/books/'+data.id)
                        .then((res) => {
                            this.getAllBooks();
                            this.$Progress.finish();
                            Swal.fire({
                                title: 'Success!',
                                text: res.data.message,
                                type: 'success',
                                timer: 3000,
                                showConfirmButton: false
                            })
                        }).catch((error) => {
                            this.$Progress.start();
                        });
                    }
                })
            }
        },
        mounted() {
            this.getAllBooks();
        }
    }
</script>
