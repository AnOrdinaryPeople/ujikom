<template>
    <div class="container">
        <ul class="nav nav-pills">
            <li v-for="(i, key) in list" :key="key" class="nav-item">
                <a
                    class="nav-link"
                    :class="pg === key ? 'active' : ''"
                    href="#"
                    @click.prevent="page(key)"
                >{{ i }}</a>
            </li>
        </ul>
        <div class="card mt-2">
            <div class="card-body">
                <pre>{{ desc[pg] }}</pre>
            </div>
            <div class="card-footer">
                <markdown class="mb-body" :content="desc[pg]" />
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "guide",
    data: () => ({
        sauce: process.env.MIX_APP_URL,
        pg: 0,
        list: ["Text", "List", "Link", "Header and Quotes", "Code", "Extras"],
        desc: [
            `*Ini teksnya miring,*
_Ini juga teksnya miring_

**Ini teksnya tebal,**
__Ini juga teksnya tebal__

~~Ini teksnya dicoret~~

_Kamu ~~**bisa** menggabungkan~~ semua_`,
            `list kalau pakai angka:

1. Satu
2. Dua
3. Tiga

list kalau pake bintang:

* Bisa juga pakai \`+\` atau \`-\`
* Mantap!

Kalau ada list lagi? kasih jarak 2 spasi:

- Satu
- Dua:
  - Dua satu
    - Dua satu satu
    - Dua satu dua
  - Dua dua`,
            `Bagaimana kalau ke [mbah gugel](https://google.com)?
disana bisa searching lho!

Kalau langsung seperti https://google.com bisa?
Bisa donk!

Kalau email bisa? nama.user@mail.com bisa!

Pingin nambah gambar? masukan link gambarnya!

![Ini gambar](https://i.imgur.com/duqfJJ9b.jpg)

**Bingung upload gambar dimana? Ke [Imgur](https://imgur.com) saja!
UI nya bagus kok**`,
            `# header paling besar
## header mengecil
### mulai mengecil
###### ini paling kecil

> Besok senin
> - Seseorang yg terkenal`,
            `Gimana cara menampilkan kodingan?
gunakan *inline code* contohnya \`select * from tables\`

Gimana kalau kodingannya lebih dari 1 baris? gunakan jarak 4 spasi

    if(menarik){
        return false;
    }
atau gunakan tanda *back quote*
\`\`\`
if(menarik){
    return true;
}
\`\`\`

Kalau kodingannya html?
\`\`\`html
<div class="container">
    <p>KQLY</p>
</div>
\`\`\`

Selain html bisa juga css, javascript, php, dan sebagainya!
`,
            `punya tugas yg belum selesai? gunakan task list:

- [x] Tugas ini sudah selesai
- [ ] Tugas ini belum selesai

Bikin tabel? caranya lumayan ribet

Nama | Kelas | Nilai
---- | ----- | -----
Udin | XII-RPL | 100
Udyn | XI-RPL | 99
Oedin | X-RPL | 98

Dan, kamu bisa menggunakan emoji :smile: ;)`
        ]
    }),
    methods: {
        page(i) {
            this.pg = i;
        }
    }
};
</script>
