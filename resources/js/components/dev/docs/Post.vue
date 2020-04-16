<template>
    <div class="container">
        <docs-component :data="txt" />
    </div>
</template>

<script>
export default {
    data: () => ({
        sauce: process.env.MIX_APP_URL,
        txt: [
            { title: "", left: "", right: "" },
            { title: "/api/post", left: "", right: "" },
            { title: "/api/post/rand", left: "", right: "" },
            { title: "/api/post/paginate/{per_page}", left: "", right: "" }
        ]
    }),
    created() {
        this.txt[0].left = `URL post:

\`${this.sauce}/api/post\` \`${this.sauce}/api/post/rand\` \`${this.sauce}/api/post/paginate/{per_page}\``;
        this.txt[1].left = `Parameter | Tipedata | Default | Deskripsi
---- | ---- | ---- | ----
limit | int | 10 | Membatasi jumlah data yang diterima
skip | int | 0 | Melewati beberapa data yang ditentukan
sort_by | string | id | Mengurutkan data yang ditentukan
sort | string | desc | Mengurutkan data dari atas atau bawah
user_id | int | *null* | Menampilkan data dari user yang ditentukan
Contoh penggunaan:

\`/api/post?limit=20\` Jumlah data menjadi 20

\`/api/post?limit=10&skip=5\` Jumlah data menjadi 10 dan data yang dilewati 5

\`/api/post?sort=asc\` Menampilkan data dari id terkecil sampai terbesar

\`/api/post?sort_by=votes&sort=desc\` Menampilkan vote terbesar sampai terkecil`;
        this.txt[1].right = `Data yang seharusnya diterima
\`\`\`js
{
    "post": [
        {
            "id": 50,
            "user_id": 5,
            "title": "Temporibus repellendus eum recusandae reiciendis",
            "desc": "Suscipit laboriosam assumenda natus est quo. Laudantium eos eius voluptas non at. Illo sint quo rerum aliquam. Quaerat qui porro tempora quam. Maxime consequatur deserunt atque voluptatem id impedit est. Ratione ut adipisci corrupti odio est facere aut unde. Rem temporibus ullam perferendis eveniet omnis aut. Voluptatem impedit omnis sed eligendi. Et nisi minus cupiditate. Ipsam accusamus et consequatur libero itaque. Dolor repellat quibusdam molestiae vel. Sint laboriosam impedit autem atque voluptatem. Iure quidem quia blanditiis veniam non sit. Corporis corrupti est atque consequatur eos. Quasi ullam est repellendus et et saepe et. Reiciendis temporibus mollitia consequatur recusandae dicta numquam dicta. Et modi dolorem earum dolore exercitationem rerum delectus. Odio aspernatur atque voluptate vitae repellendus qui aut sit. Omnis iusto ea rem consectetur.",
            "votes": 152,
            "total_comment": 7,
            "created_at": "2020-03-27 08:42:16"
        }, {
            "id": 49,
            "user_id": 4,
            "title": "Maiores nesciunt deserunt est nam",
            "desc": "Et dolor itaque voluptatum reiciendis cupiditate voluptatem ut. Explicabo dicta dignissimos laudantium debitis possimus ipsam. A porro impedit officia consequatur illum. Autem possimus commodi dignissimos quia id. Est ipsum dolorem voluptatibus tempore aliquid accusamus dicta. Provident vel possimus aspernatur nihil et. Dolor nihil porro consequatur autem. Numquam quidem dignissimos est quia. Placeat omnis quisquam ea fuga atque fugiat. Ab voluptatem animi architecto nostrum vel assumenda nihil blanditiis. Voluptas at rerum tempore iusto. Et sit officiis eum porro voluptates animi dolorem. Repellat officiis et velit iusto voluptatem quis optio. Cumque optio incidunt quo asperiores ut. Est soluta earum in aliquid. Laboriosam voluptatibus rerum quia cupiditate doloribus inventore ullam. Excepturi quidem et molestiae repellat sit consectetur asperiores ipsa. Quo dolorem fugit dolorem tenetur veniam debitis. Quis nam ratione impedit qui accusamus. Recusandae sequi voluptate dolorem quia. Harum sint molestias laborum explicabo officia nemo. Quas nihil repellendus et velit iure. Eos cumque voluptatem quidem dolorem ea accusamus. Blanditiis amet natus eveniet. Molestiae provident earum ullam nulla sapiente adipisci. Maiores magni quisquam dolores quis. Quia quae ipsum labore velit. Facilis porro ut qui rem non et nisi. Nobis esse ut iusto aspernatur doloribus dolores. Eius dolor facilis ipsum omnis perspiciatis optio quis. Minus tenetur exercitationem aut fugiat. Nulla placeat est minima. Sequi est eum in. Accusantium quis et nemo nobis pariatur. Non officiis tenetur alias culpa itaque. Enim autem omnis dolor dolor consequuntur repellat et. Et dicta dolores enim dolor. Eos animi incidunt quo voluptas incidunt est. Veniam eaque est ab perferendis aut modi quaerat.",
            "votes": 807,
            "total_comment": 16,
            "created_at": "2020-03-30 08:42:16"
        }, {
            "id": 48,
            "user_id": 24,
            "title": "Omnis soluta et rerum sit qui",
            "desc": "Molestiae fugit sequi aut dolore. Laudantium sed quod facilis error soluta exercitationem iusto autem. Odit qui perferendis non nihil expedita. Provident molestias ea aut et. Dolor omnis eum dolore ut. Dignissimos architecto aut itaque eum dolor at porro. Eveniet minus ut amet voluptas earum sapiente. Voluptatem est pariatur rerum et quasi et sint. Quis animi eligendi tempore ab soluta est. Accusantium laboriosam quo animi vitae sed. Cupiditate harum quaerat voluptas est sit. Autem eaque consequatur repellendus dolores est repudiandae id. Similique magnam temporibus qui magnam dolorem ea. Illo quibusdam facere harum voluptatem laborum. A dolores mollitia voluptatibus et esse. Fugit quaerat aut rerum consequuntur autem et et. Et ipsum autem illum et. Non commodi quis aliquid dolores. Eum et voluptas iste quia eius. Atque fugiat porro est fugiat fugiat necessitatibus. Blanditiis non enim aut ratione quaerat dolorum. Id aliquam voluptas numquam sed. Nihil architecto dolorum excepturi molestiae necessitatibus quam voluptatem. Adipisci consequatur voluptatem amet dignissimos. Dicta pariatur qui corporis quaerat quia doloremque esse iste.",
            "votes": 774,
            "total_comment": 9,
            "created_at": "2020-03-24 08:42:16"
        }, {
            "id": 47,
            "user_id": 27,
            "title": "Optio aut culpa modi",
            "desc": "Aliquam saepe deserunt consectetur voluptatem non. Velit nisi in et totam aut nulla. Autem quia dolores sint. Dolorum error enim omnis sequi in est incidunt. Modi blanditiis eligendi est omnis harum libero. Dolor vitae ex animi eum voluptas enim tenetur. Voluptas quibusdam ratione illum voluptas exercitationem. Exercitationem soluta alias itaque optio maiores maxime dolorum magni. Non praesentium veniam sequi rerum et. Est aliquid reiciendis debitis. Hic commodi accusamus perferendis qui sequi minima pariatur. Et voluptatum consequatur alias cumque et quod ut. Esse ut fugiat quam dignissimos corrupti. Sed iure ex est qui molestias. Ratione quo et quibusdam quos modi. Id blanditiis omnis quidem excepturi. Beatae et qui et maiores quod dignissimos.",
            "votes": 682,
            "total_comment": 11,
            "created_at": "2020-04-11 08:42:16"
        }, {
            "id": 46,
            "user_id": 27,
            "title": "Nulla est eligendi provident et cupiditate ad",
            "desc": "Aut quod omnis facere sequi hic qui rerum. Sapiente at explicabo autem placeat et inventore in. In repellendus eligendi repellendus quia. Aspernatur ab modi officia consequuntur quae dolorem. Minima molestiae porro esse harum consectetur similique. Nesciunt cum hic dignissimos dicta doloribus. Impedit eos accusantium quis ullam quia ullam maiores fugiat. Odit sequi vel sit ut ipsam. Maiores doloribus voluptatem odio numquam ea aut quidem. Voluptas ut dolorem possimus sit dolor voluptate sit. Corrupti cupiditate et consequatur distinctio iste velit dolorum asperiores. Facere tempora optio tempora. Qui saepe amet quos deserunt pariatur. Est ea mollitia fuga. Molestiae necessitatibus labore qui eius perspiciatis. Voluptatum deleniti laudantium perspiciatis autem. Neque non magnam minus. Quam facere et cum atque non nisi placeat. Non nihil explicabo molestias rem. Aperiam culpa et repudiandae hic ex.",
            "votes": 50,
            "total_comment": 9,
            "created_at": "2020-04-05 08:42:16"
        }
    ]
}
\`\`\``;
        this.txt[2].left = `Parameter | Tipedata | Default | Deskripsi
---- | ---- | ---- | ----
limit | int | 10 | Membatasi jumlah data yang diterima
skip | int | 0 | Melewati beberapa data yang ditentukan
Contoh penggunaan:

\`/api/post/rand?limit=20\` Jumlah data menjadi 20

\`/api/post/rand?limit=10&skip=5\` Jumlah data menjadi 10 dan data yang dilewati 5`;
        this.txt[2].right = `Data yang seharusnya diterima
\`\`\`js
{
    "post": [
        {
            "id": 29,
            "user_id": 24,
            "title": "Laborum qui voluptate nam pariatur non quis sint",
            "desc": "Delectus consequatur autem unde sed non et. Quia doloribus ea cum suscipit repellat. Iure est voluptate facere excepturi ea soluta. Quis et dolores omnis aperiam. Ipsum magni aperiam facere minima hic consequatur. Dicta veritatis eveniet quia sit. Et quia enim ipsam omnis. Eveniet beatae asperiores eius. Deleniti sit cum quis ipsa voluptatem consequatur non. Ab porro et est repellat atque laboriosam dolor. Totam nam similique laudantium aut dolorum ab ratione voluptatem. Similique corporis est quibusdam et occaecati. Et aliquam provident alias et dicta provident. Autem est sequi voluptate omnis aut molestias saepe. Nihil vel officiis provident consequatur tempore. Animi voluptas blanditiis eum accusantium eaque quae cupiditate. Neque qui tempore fuga incidunt dolore est molestiae neque.",
            "votes": 938,
            "total_comment": 11,
            "created_at": "2020-03-30 08:42:16"
        }, {
            "id": 50,
            "user_id": 5,
            "title": "Temporibus repellendus eum recusandae reiciendis",
            "desc": "Suscipit laboriosam assumenda natus est quo. Laudantium eos eius voluptas non at. Illo sint quo rerum aliquam. Quaerat qui porro tempora quam. Maxime consequatur deserunt atque voluptatem id impedit est. Ratione ut adipisci corrupti odio est facere aut unde. Rem temporibus ullam perferendis eveniet omnis aut. Voluptatem impedit omnis sed eligendi. Et nisi minus cupiditate. Ipsam accusamus et consequatur libero itaque. Dolor repellat quibusdam molestiae vel. Sint laboriosam impedit autem atque voluptatem. Iure quidem quia blanditiis veniam non sit. Corporis corrupti est atque consequatur eos. Quasi ullam est repellendus et et saepe et. Reiciendis temporibus mollitia consequatur recusandae dicta numquam dicta. Et modi dolorem earum dolore exercitationem rerum delectus. Odio aspernatur atque voluptate vitae repellendus qui aut sit. Omnis iusto ea rem consectetur.",
            "votes": 152,
            "total_comment": 7,
            "created_at": "2020-03-27 08:42:16"
        }, {
            "id": 44,
            "user_id": 23,
            "title": "Enim aut dolor odit et",
            "desc": "Exercitationem eum non minus excepturi sapiente et est tempore. Vel culpa sunt est voluptatum distinctio iste. Rerum neque ut eaque possimus repudiandae iste. Molestias debitis cum sunt dolore dolorem. Et exercitationem illum ipsa nam et praesentium qui accusantium. Quas magnam laudantium ut provident. Aut quia velit laborum. Error porro dolor minus recusandae perspiciatis. Porro iste perferendis deserunt laborum quis qui dolores. Harum harum quis ut consequatur. Neque illo distinctio similique sapiente.",
            "votes": 575,
            "total_comment": 12,
            "created_at": "2020-04-02 08:42:16"
        }, {
            "id": 12,
            "user_id": 13,
            "title": "Ut eveniet corporis nobis ut",
            "desc": "Enim rem animi et enim nihil. A laboriosam quia consequatur reprehenderit doloribus reiciendis. Debitis tempore saepe sapiente incidunt sapiente. Quaerat ipsa consequuntur et qui veniam. Est labore eos est et autem nihil. Iusto quidem qui ut porro. Et omnis dolorum odio commodi. Eaque quidem rem nulla. Quisquam et est dolore dolore vel magni. Quo atque aut impedit nam et natus et. Quod eos necessitatibus non consequuntur. Sunt ratione voluptatem quas commodi.",
            "votes": 427,
            "total_comment": 18,
            "created_at": "2020-04-03 08:42:16"
        }, {
            "id": 3,
            "user_id": 9,
            "title": "Sed maxime et neque placeat itaque magni rerum",
            "desc": "Sequi ex eum voluptas totam quos. Sunt eum recusandae et fugit expedita sed. Enim quis sed et. Autem aliquid quidem ut. Minus ullam veritatis vel aut fugiat eos. Voluptatibus fuga optio fugiat corporis molestiae architecto. Reprehenderit qui asperiores eos deleniti voluptas ducimus ipsam nihil. Ut molestiae et quo in sit autem non asperiores. Qui est incidunt quibusdam eos recusandae eos ex. Est quam saepe qui aut. Rerum magnam culpa saepe commodi eos velit dolorem nihil. Nobis ut quae illo rerum ea et ullam fugit. Qui deleniti eum quia iusto. Qui molestias ea nobis sed vel.",
            "votes": 344,
            "total_comment": 9,
            "created_at": "2020-04-11 08:42:16"
        }
    ]
}
\`\`\``;
        this.txt[3].left = `Parameter | Tipedata | Default | Deskripsi
---- | ---- | ---- | ----
sort_by | string | id | Mengurutkan data yang ditentukan
sort | string | desc | Mengurutkan data dari atas atau bawah
Contoh penggunaan:

\`/api/post/paginate?sort=asc\` Menampilkan data dari id terkecil sampai terbesar

\`/api/post/paginate/${Math.floor(
            Math.random() * 21
        )}?sort_by=name&sort=desc\` Menampilkan data dari nama Z - A

**Untuk mengubah halaman:**

\`/api/post/paginate?page=2\`

\`/api/post/paginate/${Math.floor(Math.random() * 21)}?page=2\`

\`/api/post/paginate/${Math.floor(
            Math.random() * 21
        )}?page=2&sort_by=name&sort=desc\``;
        this.txt[3].right = `Data yang seharusnya diterima
\`\`\`js
{
    "current_page": 1,
    "data": [
        {
            "id": 50,
            "user_id": 5,
            "title": "Temporibus repellendus eum recusandae reiciendis",
            "desc": "Suscipit laboriosam assumenda natus est quo. Laudantium eos eius voluptas non at. Illo sint quo rerum aliquam. Quaerat qui porro tempora quam. Maxime consequatur deserunt atque voluptatem id impedit est. Ratione ut adipisci corrupti odio est facere aut unde. Rem temporibus ullam perferendis eveniet omnis aut. Voluptatem impedit omnis sed eligendi. Et nisi minus cupiditate. Ipsam accusamus et consequatur libero itaque. Dolor repellat quibusdam molestiae vel. Sint laboriosam impedit autem atque voluptatem. Iure quidem quia blanditiis veniam non sit. Corporis corrupti est atque consequatur eos. Quasi ullam est repellendus et et saepe et. Reiciendis temporibus mollitia consequatur recusandae dicta numquam dicta. Et modi dolorem earum dolore exercitationem rerum delectus. Odio aspernatur atque voluptate vitae repellendus qui aut sit. Omnis iusto ea rem consectetur.",
            "votes": 152,
            "total_comment": 7,
            "created_at": "2020-03-27 08:42:16"
        },
        {
            "id": 49,
            "user_id": 4,
            "title": "Maiores nesciunt deserunt est nam",
            "desc": "Et dolor itaque voluptatum reiciendis cupiditate voluptatem ut. Explicabo dicta dignissimos laudantium debitis possimus ipsam. A porro impedit officia consequatur illum. Autem possimus commodi dignissimos quia id. Est ipsum dolorem voluptatibus tempore aliquid accusamus dicta. Provident vel possimus aspernatur nihil et. Dolor nihil porro consequatur autem. Numquam quidem dignissimos est quia. Placeat omnis quisquam ea fuga atque fugiat. Ab voluptatem animi architecto nostrum vel assumenda nihil blanditiis. Voluptas at rerum tempore iusto. Et sit officiis eum porro voluptates animi dolorem. Repellat officiis et velit iusto voluptatem quis optio. Cumque optio incidunt quo asperiores ut. Est soluta earum in aliquid. Laboriosam voluptatibus rerum quia cupiditate doloribus inventore ullam. Excepturi quidem et molestiae repellat sit consectetur asperiores ipsa. Quo dolorem fugit dolorem tenetur veniam debitis. Quis nam ratione impedit qui accusamus. Recusandae sequi voluptate dolorem quia. Harum sint molestias laborum explicabo officia nemo. Quas nihil repellendus et velit iure. Eos cumque voluptatem quidem dolorem ea accusamus. Blanditiis amet natus eveniet. Molestiae provident earum ullam nulla sapiente adipisci. Maiores magni quisquam dolores quis. Quia quae ipsum labore velit. Facilis porro ut qui rem non et nisi. Nobis esse ut iusto aspernatur doloribus dolores. Eius dolor facilis ipsum omnis perspiciatis optio quis. Minus tenetur exercitationem aut fugiat. Nulla placeat est minima. Sequi est eum in. Accusantium quis et nemo nobis pariatur. Non officiis tenetur alias culpa itaque. Enim autem omnis dolor dolor consequuntur repellat et. Et dicta dolores enim dolor. Eos animi incidunt quo voluptas incidunt est. Veniam eaque est ab perferendis aut modi quaerat.",
            "votes": 807,
            "total_comment": 16,
            "created_at": "2020-03-30 08:42:16"
        },
        {
            "id": 48,
            "user_id": 24,
            "title": "Omnis soluta et rerum sit qui",
            "desc": "Molestiae fugit sequi aut dolore. Laudantium sed quod facilis error soluta exercitationem iusto autem. Odit qui perferendis non nihil expedita. Provident molestias ea aut et. Dolor omnis eum dolore ut. Dignissimos architecto aut itaque eum dolor at porro. Eveniet minus ut amet voluptas earum sapiente. Voluptatem est pariatur rerum et quasi et sint. Quis animi eligendi tempore ab soluta est. Accusantium laboriosam quo animi vitae sed. Cupiditate harum quaerat voluptas est sit. Autem eaque consequatur repellendus dolores est repudiandae id. Similique magnam temporibus qui magnam dolorem ea. Illo quibusdam facere harum voluptatem laborum. A dolores mollitia voluptatibus et esse. Fugit quaerat aut rerum consequuntur autem et et. Et ipsum autem illum et. Non commodi quis aliquid dolores. Eum et voluptas iste quia eius. Atque fugiat porro est fugiat fugiat necessitatibus. Blanditiis non enim aut ratione quaerat dolorum. Id aliquam voluptas numquam sed. Nihil architecto dolorum excepturi molestiae necessitatibus quam voluptatem. Adipisci consequatur voluptatem amet dignissimos. Dicta pariatur qui corporis quaerat quia doloremque esse iste.",
            "votes": 774,
            "total_comment": 9,
            "created_at": "2020-03-24 08:42:16"
        },
        {
            "id": 47,
            "user_id": 27,
            "title": "Optio aut culpa modi",
            "desc": "Aliquam saepe deserunt consectetur voluptatem non. Velit nisi in et totam aut nulla. Autem quia dolores sint. Dolorum error enim omnis sequi in est incidunt. Modi blanditiis eligendi est omnis harum libero. Dolor vitae ex animi eum voluptas enim tenetur. Voluptas quibusdam ratione illum voluptas exercitationem. Exercitationem soluta alias itaque optio maiores maxime dolorum magni. Non praesentium veniam sequi rerum et. Est aliquid reiciendis debitis. Hic commodi accusamus perferendis qui sequi minima pariatur. Et voluptatum consequatur alias cumque et quod ut. Esse ut fugiat quam dignissimos corrupti. Sed iure ex est qui molestias. Ratione quo et quibusdam quos modi. Id blanditiis omnis quidem excepturi. Beatae et qui et maiores quod dignissimos.",
            "votes": 682,
            "total_comment": 11,
            "created_at": "2020-04-11 08:42:16"
        },
        {
            "id": 46,
            "user_id": 27,
            "title": "Nulla est eligendi provident et cupiditate ad",
            "desc": "Aut quod omnis facere sequi hic qui rerum. Sapiente at explicabo autem placeat et inventore in. In repellendus eligendi repellendus quia. Aspernatur ab modi officia consequuntur quae dolorem. Minima molestiae porro esse harum consectetur similique. Nesciunt cum hic dignissimos dicta doloribus. Impedit eos accusantium quis ullam quia ullam maiores fugiat. Odit sequi vel sit ut ipsam. Maiores doloribus voluptatem odio numquam ea aut quidem. Voluptas ut dolorem possimus sit dolor voluptate sit. Corrupti cupiditate et consequatur distinctio iste velit dolorum asperiores. Facere tempora optio tempora. Qui saepe amet quos deserunt pariatur. Est ea mollitia fuga. Molestiae necessitatibus labore qui eius perspiciatis. Voluptatum deleniti laudantium perspiciatis autem. Neque non magnam minus. Quam facere et cum atque non nisi placeat. Non nihil explicabo molestias rem. Aperiam culpa et repudiandae hic ex.",
            "votes": 50,
            "total_comment": 9,
            "created_at": "2020-04-05 08:42:16"
        }
    ],
    "first_page_url": "${this.sauce}/api/post/paginate?page=1",
    "from": 1,
    "last_page": 10,
    "last_page_url": "${this.sauce}/api/post/paginate?page=10",
    "next_page_url": "${this.sauce}/api/post/paginate?page=2",
    "path": "${this.sauce}/api/post/paginate",
    "per_page": "5",
    "prev_page_url": null,
    "to": 5,
    "total": 50
}
\`\`\``;
    }
};
</script>