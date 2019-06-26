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
                            <label for="nama">Nama Lengkap <span class="text-danger">*</span></label>
                            <input v-model="user.nama" type="text" :class="[ errors.nama ? 'is-invalid' : '' ]" class="form-control" id="nama">
                            <div v-if="errors.nama" class="invalid-feedback">
                                {{ errors.nama }}
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="umur">Umur <span class="text-danger">*</span></label>
                            <input v-model="user.umur" type="number" :class="[ errors.umur ? 'is-invalid' : '' ]" class="form-control" id="umur">
                            <div v-if="errors.umur" class="invalid-feedback">
                                {{ errors.umur }}
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

        <h3>User</h3>
        <h6 class="text-muted">Lihat semua data user disini.</h6>

        <div class="row mt-5">
            <div class="col-6">
                <h4 class="text-dark float-left">Semua Data ({{ count_users }})</h4>
            </div>
            <div class="col-6">
                <button type="button" @click="openModal('create')" class="btn btn-outline-primary float-right btn-sm">Tambah Data</button>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-12">
                <table v-if="users.length > 0" class="table">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama</th>
                            <th>Umur</th>
                            <th>Buku</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(data, key) in users">
                            <td>{{ ++key }}</td>
                            <td>{{ data.nama }}</td>
                            <td>{{ data.umur }} tahun</td>
                            <td>{{ data.books_count }}</td>
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
                    nama: '',
                    umur: ''
                },
                user: {
                    id: '',
                    nama: '',
                    umur: ''
                },
                users: {},
                count_users: 0
            }
        },
        methods: {
            closeModal() {
                this.errors.id = '';
                this.errors.nama = '';
                this.errors.umur = '';

                $('#exampleModal').modal('hide');
            },
            validate() {
                if(!this.user.nama) {
                    this.errors.nama = 'Nama lengkap harus diisi';
                }
                if(!this.user.umur) {
                    this.errors.umur = 'Umur harus diisi';
                }

                if(this.user.nama && this.user.umur) {
                    return true;
                }
            },
            openModal(type, data = null) {
                if(type === 'create') {
                    this.editMode = false;
                    this.user.id = ''
                    this.user.nama = '';
                    this.user.umur = '';
                } else {
                    this.editMode = true;
                    this.user.id = data.id;
                    this.user.nama = data.nama;
                    this.user.umur = data.umur;
                }

                $('#exampleModal').modal('show');
            },
            getAllUsers() {
                this.$Progress.start();
                axios.get('/api/users')
                .then(({ data }) => {
                    this.$Progress.finish();
                    this.users = data.users;
                    this.count_users = data.count;
                }).catch(() => {
                    this.$Progress.fail();
                })
            },
            store() {
                if(this.validate()) {
                    this.$Progress.start();
                    axios.post('/api/users', this.user)
                    .then((res) => {
                        this.getAllUsers();
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
                    axios.put('/api/users/'+ this.user.id, this.user,)
                    .then((res) => {
                        this.getAllUsers();
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
                    text: data.nama +" Akan dihapus!",
                    type: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Hapus',
                    cancelButtonText: 'Kembali'
                    }).then((result) => {
                    if (result.value) {
                        this.$Progress.start();
                        axios.delete('/api/users/'+data.id)
                        .then((res) => {
                            this.getAllUsers();
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
            this.getAllUsers();
        }
    }
</script>
