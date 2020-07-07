<template>
    <el-container>
        <el-aside width="200px">
            <el-menu :default-active="'0'" @select="changeSelect">
                <el-menu-item index="1">
                    <span slot="title">品类管理</span>
                </el-menu-item>
                <el-menu-item index="2">
                    <span slot="title">商品管理</span>
                </el-menu-item>
                <el-menu-item index="3">
                    <span slot="title">库存管理</span>
                </el-menu-item>
                <el-menu-item index="4">
                    <span slot="title">订单管理</span>
                </el-menu-item>
            </el-menu>
        </el-aside>
        <el-main>
            <TagManager v-if="status === 1" v-on:flush="flush"></TagManager>
            <GoodManager v-if="status === 2" v-on:flush="flush"></GoodManager>
            <StockManager v-if="status === 3" v-on:flush="flush"></StockManager>
            <OrderManager v-if="status === 4" v-on:flush="flush"></OrderManager>
        </el-main>
    </el-container>
</template>

<script>
    import TagManager from './Admin/TagManager'
    import GoodManager from "./Admin/GoodManager"
    import StockManager from "./Admin/StockManager"
    import OrderManager from "./Admin/OrderManager"

    export default {
        name: "Admin",
        data() {
            return {
                status: 0,
            }
        },
        components: {
            OrderManager,
            StockManager,
            GoodManager,
            TagManager
        },

        methods: {
            changeSelect(index) {
                this.status = 0
                setTimeout(() => {
                    this.status = parseInt(index)
                }, 300);
            },

            flush() {
                let lastStatus = this.status
                this.status = 0
                setTimeout(() => {
                    this.status = parseInt(lastStatus)
                }, 300);
            },
        }
    }
</script>

<style scoped>
</style>