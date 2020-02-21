<template>
    <div class="card">
        <div class="card-header">
            <ul class="nav nav-tabs card-header-tabs text-center row">
                <li class="nav-item col-4">
                    <a
                        class="nav-link"
                        :class="i === 0 ? 'active' : ''"
                        href="#"
                        @click.prevent="page(0)"
                    >Input</a>
                </li>
                <li class="nav-item col-4">
                    <a
                        class="nav-link"
                        :class="i === 1 ? 'active' : ''"
                        href="#"
                        @click.prevent="page(1)"
                    >Hasil</a>
                </li>
                <li class="nav-item col-4">
                    <a
                        class="nav-link"
                        :class="i === 2 ? 'active' : ''"
                        href="#"
                        @click.prevent="page(2)"
                    >Format</a>
                </li>
            </ul>
        </div>
        <div class="card-body">
            <div v-if="i === 0">
                <input-mark v-model="desc" :configs="config" />
                <button
                    :disabled="clicked === true"
                    class="btn btn-primary"
                    @click.prevent="send"
                >Kirim Pesan</button>
                <fa v-if="clicked" icon="spinner" spin size="lg" class="text-primary" />
            </div>
            <div v-else-if="i === 1">
                <div v-if="desc != ''">
                    <markdown class="md-body" :content="desc" />
                </div>
                <div v-else>
                    <h4 class="text-muted text-center">Pesan belum diisi</h4>
                </div>
            </div>
            <div v-else>
                <guide />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    data: () => ({
        i: 0,
        desc: "",
        config: {
            toolbar: [
                "bold",
                "italic",
                "heading",
                "|",
                "code",
                "unordered-list",
                "ordered-list",
                "|",
                "link",
                "image",
                "|",
                "strikethrough",
                "table"
            ],
            placeholder: "Ketik disini..",
            indentWithTabs: false,
            spellChecker: false,
            tabSize: 4
        },
        clicked: false
    }),
    methods: {
        page(i) {
            this.i = i;
        },
        send() {
            if (this.desc !== "") {
                this.clicked = true;
                this.$emit("send", this.desc);
            }
        },
        reset() {
            this.clicked = false;
            this.desc = "";
        },
        set(data) {
            this.desc = "";
            setTimeout(() => {
                this.desc = data;
            }, 200);
        }
    }
};
</script>
