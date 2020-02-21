<template>
    <div :id="idd" class="modal fade" tabindex="-1">
        <div class="modal-dialog" :class="type == 'edit' ? 'modal-lg' : ''">
            <div class="modal-content">
                <div
                    class="modal-header"
                    :class="type === 'alert' || type === 'danger' || type == 'warnDel' ? 'bg-danger text-light' : ''"
                >
                    <h5 v-if="type === 'alert' || type === 'danger' || type == 'warnDel'">
                        <fa icon="exclamation-triangle" />
                        {{ ' Konfirmasi Hapus' }}
                    </h5>
                    <h5 v-else-if="type == 'edit'">
                        <fa icon="pen" />
                        {{ ' Edit' }}
                    </h5>
                    <h5 v-else-if="type == 'best'">
                        <fa icon="check" />
                        {{ ' Konfirmasi Menandai Jawaban' }}
                    </h5>
                    <h5 v-else-if="type == 'warning' || type == 'report'">
                        <fa :icon="type == 'report' ? 'flag' : 'exclamation-triangle'" />
                        {{ ttl ? ttl : (type == 'report' ? ' Laporkan' : ' Beri Peringatan') }}
                    </h5>
                    <button class="close" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div v-if="type === 'danger'">
                        <p>
                            Apakah kamu yakin hapus{{ ttl ? ' \"' + ttl + '\"' : ' ini' }}?
                            <br />
                            <br />
                            <strong>Peringatan!</strong>
                            <br />Semua komentar yang ada di postingan ini akan dihapus!
                        </p>
                        <div class="form-group">
                            <label>Isi email kamu untuk mengkonfirmasi hapus</label>
                            <input class="form-control" type="text" v-model="confirm" />
                        </div>
                    </div>
                    <div v-else-if="type === 'edit'">
                        <invut
                            pg="editReply"
                            :sauce="`/user/update/reply/${obj.id}`"
                            @reply="modalEdit"
                            ref="modalInvut"
                        />
                    </div>
                    <div v-else-if="type === 'alert' || type === 'best' || type === 'warnDel'">
                        <p>{{ type === 'alert' ? 'Apakah kamu yakin hapus komentar ini?' : (type === 'best' ? 'Tandai jawaban ini?' : (type === 'warnDel' ? 'Apakah kamu yakin hapus peringatan ini?' : '')) }}</p>
                    </div>
                    <div v-else-if="type === 'warning'">
                        <form autocomplete="off" @submit.prevent="submited()">
                            <div class="form-group">
                                <label>Jenis Peringatan</label>
                                <select class="form-control" v-model="title" required>
                                    <option value="1">Pertanyaan Sama</option>
                                    <option value="2">Pertanyaan Kurang Jelas</option>
                                    <option value="3">Pertanyaan Diluar Topik</option>
                                    <option value="4">Lainnya</option>
                                </select>
                            </div>
                            <input
                                v-if="title == 1"
                                class="form-control mb-2"
                                type="text"
                                v-model="url"
                                placeholder="Bukti URL untuk pertanyaan sama.."
                                required
                            />
                            <input
                                v-if="title == 4"
                                class="form-control mb-2"
                                type="text"
                                v-model="titlee"
                                placeholder="Judul peringatan.."
                                required
                            />
                            <textarea
                                v-if="title == 4"
                                class="form-control mb-4"
                                v-model="desc"
                                placeholder="Jelaskan kenapa harus diberi peringatan.."
                                required
                            ></textarea>
                            <button class="btn btn-primary" :disabled="clicked">Kirim</button>
                            <fa v-if="clicked" icon="spinner" spin size="lg" class="text-primary" />
                        </form>
                    </div>
                    <div v-else-if="type == 'report'">
                        <form autocomplete="off" @submit.prevent="reported()">
                            <div class="form-group">
                                <label>Yang dilaporkan</label>
                                <select class="form-control" v-model="rType" required>
                                    <option value="1">Postingan</option>
                                    <option :v-if="listReply.length" value="2">Komentar</option>
                                </select>
                            </div>
                            <div v-if="rType == 2" class="form-group">
                                <label>Komentar dari user</label>
                                <select class="form-control" v-model="rUser" required>
                                    <option
                                        v-for="(i, key) in listReply"
                                        :key="key"
                                        :value="i.id"
                                    >{{ i.name }}</option>
                                </select>
                            </div>
                            <textarea
                                class="form-control mb-4"
                                v-model="descc"
                                placeholder="Jelaskan kenapa postingan / komentar ini harus dilaporkan"
                                required
                            ></textarea>
                            <button class="btn btn-primary" :disabled="clicked">Kirim</button>
                            <fa v-if="clicked" icon="spinner" spin size="lg" class="text-primary" />
                        </form>
                    </div>
                </div>
                <div
                    v-if="type == 'alert' || type == 'danger' || type == 'best'"
                    class="modal-footer"
                >
                    <button class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                    <button
                        class="btn"
                        :class="type == 'alert' || type == 'danger' ? 'btn-danger' : 'btn-success'"
                        @click.prevent="yes"
                        :disabled="(type == 'danger' && this.$auth.user().email !== confirm) || clicked"
                    >Ya</button>
                    <fa v-if="clicked" icon="spinner" spin size="lg" class="text-primary" />
                </div>
                <div v-else-if="type == 'warnDel'" class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Tidak</button>
                    <button class="btn btn-danger" :disabled="clicked" @click.prevent="delWarn">Ya</button>
                    <fa v-if="clicked" icon="spinner" spin size="lg" class="text-primary" />
                </div>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    props: ["idd", "ttl", "type", "sauce"],
    data: () => ({
        confirm: "",
        clicked: false,
        obj: { id: 0 },
        title: "",
        desc: "",
        url: "",
        titlee: "",
        descc: "",
        rType: "",
        rUser: 0,
        listReply: []
    }),
    mounted() {
        if (this.type === "report")
            axios
                .post(
                    `user/detail/reply/${this.$route.params.id}/${
                        this.$auth.user().id
                    }`
                )
                .then(resp => (this.listReply = resp.data))
                .catch(err => console.error(err.response));
    },
    methods: {
        yes() {
            this.clicked = true;
            axios
                .post(this.sauce)
                .then(resp => {
                    $("#" + this.idd).modal("hide");

                    switch (this.type) {
                        case "danger":
                            this.$router.push({ path: "/home" });
                            break;
                        case "alert":
                            this.modalEdit({
                                id: "detail-answer",
                                paginate: this.$route.query.page || 1
                            });
                            break;
                        case "best":
                            this.modalEdit({
                                id: "answer-top",
                                paginate: this.$route.query.page || 1,
                                best: resp.data.best,
                                child: resp.data.child_best,
                                vote: resp.data.voted,
                                cVote: resp.data.child_vote
                            });
                            break;

                        default:
                            break;
                    }
                    this.clicked = false;
                })
                .catch(err => console.error(err.response));
        },
        submited() {
            var titel, dsc;
            this.clicked = true;
            switch (this.title) {
                case "1":
                    titel = "Pertanyaan Sama";
                    dsc = "Pertanyaan sudah dijawab di " + this.url;
                    break;
                case "2":
                    titel = "Pertanyaan Kurang Jelas";
                    dsc = "Jelaskan lebih detail untuk pertanyaan ini";
                    break;
                case "3":
                    titel = "Pertanyaan Diluar Topik";
                    dsc =
                        "Pertanyaan disini hanya untuk kodingan, error, saran kodingan, dan sebagainya";
                    break;
                case "4":
                    titel = this.titlee;
                    dsc = this.desc;
                    break;

                default:
                    break;
            }
            axios
                .post(this.sauce, {
                    title: titel,
                    desc: dsc
                })
                .then(resp => {
                    $("#" + this.idd).modal("hide");
                    this.$emit("modalEdyd", [
                        titel,
                        dsc,
                        resp.data.u.name,
                        resp.data.u.id,
                        resp.data.p.id
                    ]);
                    this.clicked = false;
                })
                .catch(err => console.error(err.response));
        },
        delWarn() {
            this.clicked = true;

            axios.post(this.sauce).then(() => {
                $("#" + this.idd).modal("hide");
                this.$emit("modalEdyd", ["", "", "", "", ""]);
                this.clicked = false;
            });
        },
        reported() {
            this.clicked = true;

            axios
                .post(this.sauce, {
                    desc: this.descc,
                    type: this.rType,
                    suspect_id: this.rUser
                })
                .then(() => {
                    $("#" + this.idd).modal("hide");
                    this.clicked = false;
                    this.descc = "";
                    this.rType = "";
                    this.rUser = 0;
                });
        },
        setObj(data, id) {
            this.obj = data;
            if (this.type === "edit")
                this.$refs.modalInvut.setData(this.obj, id);
        },
        modalEdit(data) {
            $("#" + this.idd).modal("hide");
            this.$emit("modalEdyd", data);
        }
    }
};
</script>
