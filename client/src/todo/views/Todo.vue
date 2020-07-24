<template>
    <!-- <div class="main-container"> -->
    <v-container class="mx-auto">
        <!-- タイトル -->
        <!-- <v-row justify="center">
            <v-col> {{ userName }}さんのTODOリスト </v-col>
        </v-row> -->
        <!-- 作成 -->
        <v-row justify="center">
            <v-col col="12">
                <v-card>
                    <v-container>
                        <v-row>
                            <v-col>
                                <v-text-field
                                    v-model="item.subject"
                                    label="やりたいこと"
                                    required
                                    @keyup.enter.ctrl="newEnter"
                                >
                                </v-text-field>
                            </v-col>
                        </v-row>

                        <v-row>
                            <v-col cols="6" sm="6" md="4">
                                <v-menu
                                    v-model="showDatePicker"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    transition="scale-transition"
                                    offset-y
                                    min-width="290px"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="item.limit_at_day"
                                            label="締切日"
                                            append-icon="event"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                            ref="limit_day"
                                        ></v-text-field>
                                    </template>
                                    <v-date-picker
                                        v-model="item.limit_at_day"
                                        locale="jp-ja"
                                        :day-format="
                                            (date) => new Date(date).getDate()
                                        "
                                        @input="showDatePicker = false"
                                    ></v-date-picker>
                                </v-menu>
                            </v-col>
                            <v-col cols="6" sm="6" md="4">
                                <v-menu
                                    ref="timePicker"
                                    v-model="showTimePicker"
                                    :close-on-content-click="false"
                                    :nudge-right="40"
                                    :return-value.sync="time"
                                    transition="scale-transition"
                                    offset-y
                                    max-width="290px"
                                    min-width="290px"
                                >
                                    <template v-slot:activator="{ on, attrs }">
                                        <v-text-field
                                            v-model="item.limit_at_time"
                                            label="時刻"
                                            append-icon="access_time"
                                            readonly
                                            v-bind="attrs"
                                            v-on="on"
                                        ></v-text-field>
                                    </template>
                                    <v-time-picker
                                        v-if="showTimePicker"
                                        v-model="item.limit_at_time"
                                        full-width
                                        @click:minute="
                                            $refs.timePicker.save(time)
                                        "
                                        @input="menu2 = false"
                                    ></v-time-picker>
                                </v-menu>
                            </v-col>
                        </v-row>
                        <v-row>
                            <v-col>
                                <v-textarea
                                    label="詳細"
                                    outline
                                    v-model="item.detail"
                                ></v-textarea>
                            </v-col>
                        </v-row>
                        <v-row justify="center">
                            <v-col cols="6" sm="4" md="4" offset-sm2>
                                <v-btn
                                    class="btn"
                                    block
                                    color="secondary"
                                    @click="clear"
                                    >クリア</v-btn
                                ></v-col
                            >
                            <v-col cols="6" sm="4" md="4" v-show="!isUpdate">
                                <v-btn
                                    class="btn"
                                    block
                                    color="primary"
                                    @click="create"
                                    >追加</v-btn
                                >
                            </v-col>
                            <v-col cols="6" sm="4" md="4" v-show="isUpdate">
                                <v-btn
                                    class="btn"
                                    block
                                    color="success"
                                    @click="update(item)"
                                    >更新</v-btn
                                >
                            </v-col>
                        </v-row>
                    </v-container>
                </v-card>
            </v-col>
        </v-row>
        <!-- リストコントロール部 -->
        <v-row justify="end" align="center">
            <!-- <v-col cols="6" sm="4" md="3" lg="2"> -->
            <v-col>
                <v-switch
                    v-model="showDoneTodo"
                    label="完了した項目を表示"
                    class="mx-1"
                ></v-switch>
            </v-col>
        </v-row>
        <!-- リスト -->
        <v-row justify-start>
            <v-col
                col="12"
                sm="6"
                v-for="(group, index) in itemGroups"
                :key="index"
            >
                <todo-list-group
                    ref="listGroup"
                    :title="group.title"
                    :headerColor="group.headerColor"
                    :items="group.lists"
                    :sortColumn="group.sortColumn"
                    :sortOrder="group.order"
                    :showDoneTodo="showDoneTodo"
                    @edit="edit"
                    @update="update"
                ></todo-list-group>
            </v-col>
        </v-row>
    </v-container>
    <!-- </div> -->
</template>

<script>
import axios from "axios";
import moment from "moment";
import TodoListGroup from "../components/TodoListGroup";

export default {
    components: { TodoListGroup },
    metaInfo: {
        title: "TODOリスト",
        htmlAttrs: {
            lang: "ja",
        },
    },
    data() {
        return {
            userName: "",
            date: new Date().toISOString().substr(0, 10),
            time: null,
            showDatePicker: false,
            showTimePicker: false,
            isUpdate: false,
            showDoneTodo: false,
            // sortItems: [
            //     { column: "limit_at", sort: "desc", text: "期日順(未来)" },
            //     { column: "limit_at", sort: "asc", text: "期日順(過去)" },
            //     { column: "created_at", sort: "desc", text: "作成順(新しい)" },
            //     { column: "created_at", sort: "asc", text: "作成順(古い)" },
            // ],
            // sortMode: "期日順",
            item: {},
            allItems: [],
            itemGroups: [],
        };
    },
    watch: {},
    created() {
        // 画面表示前、ログイン済みでなければログイン画面へ
        if (this.$store.getters["auth/user"] == null) {
            this.$router.push("/login");
            return;
        }

        // 画面表示時にリストを取得
        this.userName = this.$store.getters["auth/user"].name;
        this.getList();
    },
    computed: {
        /**
         * ユーザ情報を取得する
         */
        // user() {
        //     alert(`Todo.vue:${this.$store.getters["auth/user"]}`);
        //     return this.$store.getters["auth/user"];
        // },
    },
    methods: {
        /**
         * リスト取得(select)
         */
        async getList() {
            const res = await axios.get("/api/todo");
            this.allItems = res.data;

            // 表示用データを保持する
            for (const item of this.allItems) {
                this.createDispInfo(item);
            }

            // グループごとの項目リストを作成する
            this.createItemList();
        },

        /**
         * 新規追加(insert)
         */
        async create() {
            // 締切日時を設定する
            this.item.limit_at = this.getDate(
                this.item.limit_at_day,
                this.item.limit_at_time
            );

            // 登録
            const res = await axios.post("/api/todo", this.item);

            // リスト再構築
            this.createDispInfo(res.data);
            this.allItems.push(res.data);
            this.createItemList();

            // 入力項目をクリア
            this.clear();
        },

        /**
         * 更新処理(update)
         */
        async update(targetItem) {
            // 締切日時を設定する
            targetItem.limit_at = this.getDate(
                targetItem.limit_at_day,
                targetItem.limit_at_time
            );

            // 完了日時を設定する
            targetItem.complete_at = targetItem.checked ? new moment() : null;

            // 更新処理
            await axios.put(`/api/todo/${targetItem.id}`, targetItem);

            // グループごとの項目リストを作成する
            this.createItemList();
            this.clear();
        },

        /**
         * 項目リスト作成
         */
        createItemList() {
            this.itemGroups = [];

            // 締め切りあり
            const limitArray = this.allItems.filter((item) => {
                return item.limit_at != null;
            });
            this.itemGroups.push({
                title: "締め切りあり",
                sortColumn: "limit_at",
                headerColor: "#B71C1C",
                order: "asc",
                lists: limitArray,
            });

            // 締め切りなし
            const noLimitArray = this.allItems.filter((item) => {
                return item.limit_at == null;
            });
            this.itemGroups.push({
                title: "いつでも",
                headerColor: "#0D47A1",
                sortColumn: "created_at",
                order: "desc",
                lists: noLimitArray,
            });
        },

        createDispInfo(item) {
            if (item.limit_at != null) {
                const now = moment();
                const limit = moment(item.limit_at);
                item.limit_at_day = limit.format("YYYY-MM-DD");
                item.limit_at_time = limit.format("HH:mm");
                item.isPastDate = limit.isBefore(now);
                item.isToday =
                    limit.format("YYYY-MM-DD") == now.format("YYYY-MM-DD");
            }
            item.checked = item.complete_at != null ? true : false;
        },

        /**
         * 日付・時間から日時文字列を返却
         */
        getDate(day, time) {
            // 日付・時刻ともに未設定
            if (day == null && time == null) {
                return null;
            }

            if (day != null && time != null) {
                // 両方設定あり
                return `${day} ${time}`;
            } else if (day != null) {
                // 日付のみ設定ありの場合、0時として扱う
                return `${day} 00:00`;
            }
            // 時刻のみ設定ありの場合、今日として扱う
            return `${moment().format("YYYY-MM-DD")} ${time}`;
        },

        /**
         * 項目クリア
         */
        clear() {
            this.item = {
                subject: "", // 項目名
                detail: "", // 詳細
                // category_id: -1, // カテゴリーID(TODO)
                limit_at: null, // 期限
                limit_at_day: null,
                limit_at_time: null,
                complete_at: null, // 完了日時
            };
            this.isUpdate = false;
        },

        /**
         * 項目入力でCtrl+Enter押下時、簡易登録する
         */
        newEnter() {
            if (this.item.id != null) {
                this.update(this.item);
            } else {
                this.create();
            }
        },

        /**
         * エンターキー押下時にunfocusする
         */
        unforcus() {
            if (event.keyCode !== 13) {
                return;
            }
            document.activeElement.blur();
        },

        /**
         * 編集対象として選択
         */
        edit(currItem) {
            this.item = currItem;
            this.isUpdate = true;
        },
    },
};
</script>

<style>
.main-container {
    width: 500px;
    margin: auto;
}
</style>
