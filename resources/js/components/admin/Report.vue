<template>
    <div class="container-fluid">
        <div v-if="loaded" class="row">
            <div class="col-lg-9 col-md-9 col-sm-12">
                <input
                    v-if="data.data.length"
                    class="form-control col-lg-6 col-md-12 col-sm-12 mx-auto my-3"
                    type="text"
                    v-model="q"
                    placeholder="Cari nama.."
                />
                <table v-if="data.data.length" class="table table-striped">
                    <thead>
                        <tr>
                            <th>Dari User</th>
                            <th>Tipe</th>
                            <th>Deskripsi</th>
                            <th>Tindakan</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="(i, key) in list" :key="key">
                            <td>{{ i.name }}</td>
                            <td>{{ i.type == 1 ? 'Postingan' : 'User' }}</td>
                            <td>{{ i.desc | limit }}</td>
                            <td>
                                <button
                                    class="btn btn-info"
                                    title="Detail"
                                    @click="open(key, 'report-detail')"
                                >
                                    <fa icon="eye" />
                                </button>
                                <button
                                    v-if="i.read"
                                    class="btn btn-success"
                                    title="Lihat Tanggapan"
                                    @click="openRepDet(i.id, key)"
                                >
                                    <fa icon="reply-all" />
                                </button>
                                <button
                                    v-else
                                    class="btn btn-primary"
                                    title="Beri Tanggapan"
                                    @click="open(key, 'report-reply')"
                                >
                                    <fa icon="reply" />
                                </button>
                                <button
                                    class="btn btn-danger"
                                    title="Hapus"
                                    @click="open(key, 'report-delete')"
                                >
                                    <fa icon="trash" />
                                </button>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <table v-else class="table">
                    <thead>
                        <tr class="text-center">
                            <th>Tidak ada laporan</th>
                        </tr>
                    </thead>
                </table>
            </div>
            <div class="col-lg-3 col-md-3 col-sm-12">
                <div class="sticky-top pt-3">
                    <div class="card mb-3">
                        <div class="card-header">Urutkan Tabel</div>
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
        <admin-modal idd="report-detail" title="Detail" icon="info">
            <div slot="body">
                <h5>Laporan dari {{ d.name }}</h5>
                <p>{{ d.desc }}</p>
            </div>
        </admin-modal>
        <admin-modal
            idd="report-reply"
            title="Beri Tanggapan"
            size="modal-lg"
            icon="reply"
            :is-form="true"
        >
            <div slot="body">
                <h5>Beri tanggapan ke {{ d.name }}?</h5>
                <div class="form-group">
                    <admin-invut @send="sendReply" ref="invut" />
                </div>
            </div>
        </admin-modal>
        <admin-modal
            idd="report-reply-detail"
            title="Lihat Tanggapan"
            icon="reply-all"
            size="modal-lg"
        >
            <div slot="body">
                <markdown v-if="d.desc !== undefined" class="mb-body" :content="d.desc" />
            </div>
        </admin-modal>
        <admin-modal
            idd="report-delete"
            title="Konfirmasi Hapus"
            icon="exclamation-triangle"
            bg-title="bg-danger text-light"
            :is-form="true"
            @send="del"
        >
            <div slot="body">
                <div class="form-group">
                    <label>Beri catatan kenapa laporan ini harus dihapus</label>
                    <input class="form-control" type="text" v-model="desc" required />
                    <small class="text-muted">Catatan ini hanya untuk admin</small>
                </div>
            </div>
            <div slot="footer">
                <button class="btn btn-danger" :disabled="clicked">Kirim</button>
                <fa v-if="clicked" icon="spinner" spin size="lg" class="text-primary" />
            </div>
        </admin-modal>
    </div>
</template>

<script>
export default {
    data: () => ({
        loaded: false,
        data: { data: [] },
        q: "",
        sort: [
            { name: "Terbaru", icon: "sort-amount-up" },
            { name: "Terakhir", icon: "sort-amount-down" },
            { name: "Nama A-Z", icon: "sort-alpha-up" },
            { name: "Nama Z-A", icon: "sort-alpha-down" },
            { name: "Belum Ditanggapi", icon: "reply" },
            { name: "Sudah Ditanggapi", icon: "reply-all" }
        ],
        sortKey: 0,
        d: {},
        desc: "",
        clicked: false
    }),
    mounted() {
        this.getData();
    },
    methods: {
        changePage(page = 1) {
            axios.post("admin/reports?page=" + page).then(resp => {
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
                    return d.sort(a => (a.read === 1 ? 1 : -1));
                    break;
                case 5:
                    return d.sort(a => (a.read === 0 ? 1 : -1));
                    break;

                default:
                    break;
            }
        },
        open(key, id) {
            this.d = this.data.data[key];
            $("#" + id).modal();
        },
        sendReply(data) {
            axios
                .post(`admin/reports/reply/${this.d.id}/${this.d.user_id}`, {
                    desc: data
                })
                .then(() => {
                    $("#report-reply").modal("hide");
                    this.$refs.invut.reset();

                    this.getData();
                });
        },
        openRepDet(id, key) {
            axios
                .post(`admin/reports/rep/${this.data.data[key].id}`)
                .then(resp => {
                    this.d = resp.data;
                    $("#report-reply-detail").modal();
                });
        },
        del() {
            this.clicked = true;
            axios
                .post(`admin/reports/destroy/${this.d.id}/${this.d.user_id}`, {
                    desc: this.desc
                })
                .then(() => {
                    $("#report-delete").modal("hide");
                    this.desc = "";

                    this.getData();
                    this.clicked = false;
                });
        },
        getData() {
            var p = this.$route.query.page;

            axios
                .post(`admin/reports${p !== undefined ? "?page=" + p : ""}`)
                .then(resp => {
                    this.data = resp.data;
                    this.loaded = true;
                });
        }
    },
    computed: {
        list() {
            return this.q !== ""
                ? this.data.data.filter(
                      e =>
                          e.name.toLowerCase().indexOf(this.q.toLowerCase()) >
                          -1
                  )
                : this.data.data;
        }
    },
    filters: {
        limit(str) {
            return str.length > 50 ? str.substring(0, 50) + "..." : str;
        }
    }
};
</script>
