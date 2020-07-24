<template>
    <div>
        <div class="main-container">
            <h2>新規ユーザー登録</h2>
            <v-form ref="form" v-model="valid" :lazy-validation="lazy">
                <v-text-field
                    v-model="form.name"
                    :rules="nameRules"
                    label="お名前"
                    required
                    autocomplete="no"
                ></v-text-field>
                <v-text-field
                    v-model="form.email"
                    :rules="emailRules"
                    label="メールアドレス"
                    required
                    autocomplete="no"
                ></v-text-field>
                <v-text-field
                    v-model="form.password"
                    :rules="passwordRules"
                    label="パスワード"
                    required
                    type="password"
                    autocomplete="no"
                ></v-text-field>
                <v-text-field
                    v-model="form.password_confirmation"
                    :rules="repasswordRules"
                    label="パスワード(確認)"
                    required
                    type="password"
                    autocomplete="no"
                ></v-text-field>
                <v-btn :disabled="!valid" @click="register">送信</v-btn>
            </v-form>
        </div>
    </div>
</template>

<script>
export default {
    components: {},
    metaInfo: {
        title: "会員登録",
        htmlAttrs: {
            lang: "ja",
        },
    },
    created() {
        alert("Register.vue");
        // 画面表示時、既にログイン済みならTODOリストへ遷移する
        const user = this.$store.getters["auth/user"];
        if (user !== null) {
            this.$router.push("/todo");
        }
    },
    data() {
        return {
            valid: true,
            form: {
                name: "",
                email: "",
                password: "",
                password_confirmation: "",
            },
            nameRules: [(v) => !!v || "名前を入力してください"],
            emailRules: [
                (v) => !!v || "E-mailを入力してください",
                (v) =>
                    /^\w+([.-]?\w+)*@\w+([.-]?\w+)*(\.\w{2,3})+$/.test(v) ||
                    "E-mailを正しく入力してください",
            ],
            passwordRules: [
                (v) => !!v || "パスワードを入力してください",
                (v) =>
                    v.length >= 8 || "パスワードは8文字以上で入力してください",
            ],
            repasswordRules: [
                (v) => !!v || "パスワードの再入力を行ってください",
                (v) => v == this.form.password || "パスワードが一致しません",
            ],
        };
    },
    methods: {
        async register() {
            if (!this.$refs.form.validate()) {
                alert("入力内容を確認してください");
            }
            await this.$store.dispatch("auth/register", this.form);
            this.$router.push("/todo");
        },
    },
};
</script>

<style>
.main-container {
    width: 500px;
    margin: auto;
}
.disp-login {
    display: none;
}
</style>
