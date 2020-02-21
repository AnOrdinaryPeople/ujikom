<template>
    <div class="container-fluid">
        <div v-if="loaded" class="row">
            <div class="col-lg-9 col-md-9 col-sm-12">
                <div>
                    <input
                        class="form-control col-lg-6 col-md-12 col-sm-12 mx-auto my-3"
                        type="text"
                        v-model="q"
                        placeholder="Cari nama / email.."
                    />
                    <div v-if="list.length" class="card-columns">
                        <div v-for="(i, key) in list" :key="key" class="card">
                            <img :src="i.avatar" class="card-img-top" />
                            <div class="card-img-overlay">
                                <button
                                    class="btn btn-info"
                                    title="detail"
                                    @click="detail(key, 'user-view')"
                                >
                                    <fa icon="eye" />
                                </button>
                                <button
                                    class="btn btn-primary"
                                    title="Beri Pesan"
                                    @click="detail(key, 'user-notif')"
                                >
                                    <fa icon="bell" />
                                </button>
                                <button
                                    class="btn btn-danger"
                                    title="hapus"
                                    @click="detail(key, 'user-del')"
                                >
                                    <fa icon="trash" />
                                </button>
                            </div>
                            <div class="card-body">
                                <h5 class="card-title">{{ i.name }}</h5>
                                <p class="card-text">
                                    {{ i.email + ' ' }}
                                    <small
                                        v-if="i.email_verified_at"
                                        class="text-success"
                                    >
                                        <fa icon="check" />
                                        {{ ' Terverifikasi' }}
                                    </small>
                                    <small v-else class="text-danger">
                                        <fa class="text-danger" icon="times" />
                                        {{ ' Belum diverifikasi' }}
                                    </small>
                                </p>
                            </div>
                        </div>
                    </div>
                    <div v-else>
                        <h4 class="text-center">User {{ q }} tidak ditemukan</h4>
                    </div>
                </div>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12">
                <div class="sticky-top pt-3">
                    <div class="card mb-3">
                        <div class="card-header">Urutkan Kartu</div>
                        <ul class="list-group list-group-flush">
                            <li
                                v-for="(i, key) in sort"
                                :key="key"
                                class="list-group-item custom-hover"
                                :class="key === sortKey ? 'text-primary' : ''"
                                @click="orderBy(key)"
                            >
                                <fa :icon="i.icon" />
                                {{ ' ' + i.name }}
                            </li>
                        </ul>
                    </div>
                    <paginate :data="data" @pagination-change-page="changePage" />
                </div>
            </div>
        </div>
        <div v-else class="text-center">
            <fa icon="spinner" spin size="2x" class="text-primary" />
        </div>
        <admin-modal idd="user-view" title="Detail User" icon="eye">
            <div slot="body">
                <div class="text-center">
                    <img class="img-fluid" :src="d.avatar" />
                    <h4>{{ d.name }}</h4>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>Email</label>
                            <h5>{{ d.email }}</h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>Verifikasi Email</label>
                            <h5 v-if="d.email_verified_at" class="text-success">
                                <fa icon="check" />
                                {{ ' Terverifikasi' }}
                            </h5>
                            <h5 v-else class="text-danger">
                                <fa icon="times" />
                                {{ ' Belum diverifikasi' }}
                            </h5>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="form-group">
                            <label>Jumlah Postingan</label>
                            <h5>{{ d.post }}</h5>
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="form-group">
                            <label>Tanggal Akun Dibuat</label>
                            <h5>{{ new Date(d.created_at).toLocaleDateString('id-ID') }}</h5>
                        </div>
                    </div>
                </div>
            </div>
        </admin-modal>
        <admin-modal
            idd="user-notif"
            title="Beri Pesan"
            icon="bell"
            size="modal-lg"
            :is-form="true"
        >
            <div slot="body">
                <div class="form-group">
                    <label>Beri pesan ke {{ d.name }}</label>
                    <admin-invut @send="send" ref="invut" />
                </div>
            </div>
        </admin-modal>
        <admin-modal
            idd="user-del"
            title="Konfirmasi Hapus"
            icon="exclamation-triangle"
            bg-title="bg-danger text-light"
            :is-form="true"
            @send="del"
        >
            <div slot="body">
                <p>
                    Apakah anda yakin akun {{ d.name }}?
                    <br />
                    <br />
                    <strong>Peringatan!</strong>
                    <br />Semua postingan, komentar, vote, peringatan, dan laporan akan benar-benar dihapus!
                </p>
                <div class="form-group">
                    <label>Isi email user tersebut untuk mengkonfirmasi hapus</label>
                    <input class="form-control" type="text" v-model="confirm" required />
                </div>
                <div class="form-group">
                    <label>Beri catatan kenapa user ini harus dihapus</label>
                    <input class="form-control" type="text" v-model="dDesc" required />
                    <small class="text-muted">Catatan ini hanya untuk admin</small>
                </div>
            </div>
            <div slot="footer">
                <button class="btn btn-danger" :disabled="d.email !== confirm || clicked">Kirim</button>
                <fa v-if="clicked" icon="spinner" spin size="lg" class="text-primary" />
            </div>
        </admin-modal>
    </div>
</template>

<script>
export default {
    data: () => ({
        data: {},
        loaded: false,
        sort: [
            { name: "Terbaru", icon: "sort-amount-up" },
            { name: "Terakhir", icon: "sort-amount-down" },
            { name: "Nama A-Z", icon: "sort-alpha-up" },
            { name: "Nama Z-A", icon: "sort-alpha-down" },
            { name: "Email A-Z", icon: "sort-alpha-up" },
            { name: "Email Z-A", icon: "sort-alpha-down" }
        ],
        sortKey: 0,
        d: {
            id: 0,
            name: "",
            email: "",
            email_verified_at: "",
            avatar: "",
            created_at: "",
            post: 0
        },
        clicked: false,
        q: "",
        confirm: "",
        dDesc: ""
    }),
    mounted() {
        var p = this.$route.query.page;
        axios
            .post(`admin/users${p !== undefined ? "?page=" + p : ""}`)
            .then(resp => {
                this.data = resp.data;
                this.loaded = true;
            });
    },
    methods: {
        changePage(page = 1) {
            axios.post("admin/users?page=" + page).then(resp => {
                this.data = resp.data;
                this.orderBy(this.sortKey);
                this.$router
                    .replace({
                        query: {
                            ...this.$route.query,
                            page: page
                        }
                    })
                    .catch(err => {});
            });
        },
        orderBy(key) {
            var d = this.data.data;
            this.sortKey = key;

            switch (key) {
                case 0:
                    return d.sort((a, b) =>
                        a.id < b.id ? 1 : b.id < a.id ? -1 : 0
                    );
                    break;
                case 1:
                    return d.sort((a, b) =>
                        a.id > b.id ? 1 : b.id > a.id ? -1 : 0
                    );
                    break;
                case 2:
                    return d.sort((a, b) =>
                        a.name > b.name ? 1 : b.name > a.name ? -1 : 0
                    );
                    break;
                case 3:
                    return d.sort((a, b) =>
                        a.name < b.name ? 1 : b.name < a.name ? -1 : 0
                    );
                    break;
                case 4:
                    return d.sort((a, b) =>
                        a.email > b.email ? 1 : b.email > a.email ? -1 : 0
                    );
                    break;
                case 5:
                    return d.sort((a, b) =>
                        a.email < b.email ? 1 : b.email < a.email ? -1 : 0
                    );
                    break;

                default:
                    break;
            }
        },
        detail(key, id) {
            this.d = this.data.data[key];
            $("#" + id).modal();
        },
        send(data) {
            this.clicked = true;
            axios
                .post("admin/users/notif/send/" + this.d.id, {
                    desc: data
                })
                .then(() => {
                    this.clicked = false;
                    $("#user-notif").modal("hide");
                    this.$refs.invut.reset();
                });
        },
        del() {
            this.clicked = true;
            axios
                .delete(`admin/users/destroy/${this.d.id}/${btoa(this.dDesc)}`)
                .then(resp => {
                    this.changePage();
                    this.clicked = false;
                    $("#user-del").modal("hide");
                })
                .catch(err => console.error(err.response));
        }
    },
    computed: {
        list() {
            let filter = this.data.data;

            if (this.q !== "") {
                filter = this.data.data.filter(
                    e =>
                        e.name.toLowerCase().indexOf(this.q.toLowerCase()) >
                            -1 ||
                        e.email.toLowerCase().indexOf(this.q.toLowerCase()) > -1
                );
            }

            return filter;
        }
    }
};
</script>
