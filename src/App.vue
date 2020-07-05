<template>
    <el-container>
        <el-header>
            <div v-if="status !== 0">
                <el-avatar class="item header" v-bind:class="{headerAdmin: admin}">{{userName}}
                </el-avatar>
            </div>
        </el-header>
        <el-main>
            <div id="app">
                <Login v-if="status===0" v-on:login="login"></Login>
                <Customer v-if="status === 1" :user-id="userId"></Customer>
                <Admin v-if="status === 2" :user-id="userId"></Admin>
            </div>
        </el-main>
    </el-container>
</template>

<script>
    import Login from "./components/Login";
    import Customer from "./components/Customer";
    import Admin from "./components/Admin";
    import Vue from 'vue'
    import ElementUI from 'element-ui'

    import 'element-ui/lib/theme-chalk/index.css'
    import './static/Main.css'

    Vue.use(ElementUI)

    export default {
        name: 'App',
        components: {
            Admin,
            Login,
            Customer
        },

        data() {
            return {
                /**
                 * 0 for login and register
                 * 1 for  customer
                 */
                status: 0,
                userId: 0,
                userName: "123",
                admin: false
            }
        },

        methods: {
            login(user) {
                this.userId = user.id
                this.admin = user.admin
                this.userName = user.user
                console.log(this.admin)
                if (this.admin === true) {
                    this.status = 2
                    this.$message.success('欢迎，管理员 ' + user.user)
                } else {
                    this.status = 1
                    this.$message.success('欢迎，' + user.user)
                }
            }
        }
    }
</script>

<style>
    #app {
        font-family: "Helvetica Neue", Helvetica, "PingFang SC", "Hiragino Sans GB", "Microsoft YaHei", "微软雅黑", Arial, sans-serif;
        -webkit-font-smoothing: antialiased;
        -moz-osx-font-smoothing: grayscale;
        text-align: center;
        color: #2c3e50;
        margin-top: 0;
    }

    .header {
        float: right;
        text-align: center;
        line-height: 60px;
        width: 60px;
        height: 60px;
        background-color: #409EFF;
        transition: 1s;
        overflow: hidden;
    }

    .headerAdmin {
        background-color: #F56C6C;
    }
</style>
