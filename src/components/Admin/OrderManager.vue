<template>
    <transition name="el-fade-in-linear">
        <!--订单-->
        <div>
            <div class="background">
                <div v-for="(o, index) in order" :key="index">
                    <transition name="fade" appear>
                        <div class="item round-small">
                            <h1>{{o.time}}</h1>
                            <h2>费用：{{o.totalCost}}</h2>
                            <p>状态：{{statusName(o.status)}}</p>
                            <el-button type="primary" round v-on:click="getDetail(o.id)">详情</el-button>
                        </div>
                    </transition>
                </div>
            </div>

            <el-dialog title="订单" :visible.sync="showDialog">
                <!--订单详情-->
                <div>
                    <div class="background">
                        <div v-for="(o, index) in detail.data" :key="index">
                            <transition name="fade" appear>
                                <div class="item round">
                                    <h1>{{goods[o.gid].name}}</h1>
                                    <!--suppress HtmlUnknownTarget -->
                                    <img :src="goods[o.gid].img" alt="Image">
                                    <div style="height: 20px">
                                        <template v-for="t in goods[o.gid].tag">
                                            <el-tag v-if="tags[t].work===true" :key="t" class="tag">
                                                {{tags[t].tag}}
                                            </el-tag>
                                        </template>
                                    </div>

                                    <h2>数量：{{o.count}}</h2>
                                    <p>价格：{{o.cost}}</p>
                                </div>
                            </transition>
                        </div>
                    </div>
                    <h1>总计：{{total}}</h1>
                    <el-button type="primary" round :disabled="detail.status !== 1">发货</el-button>
                </div>
            </el-dialog>
        </div>
    </transition>
</template>

<script>
    export default {
        name: "OrderManager",

        data() {
            return {
                tags: [
                    {id: 1, tag: 'abc', work: true},
                    {id: 2, tag: 'def', work: true},
                    {id: 3, tag: 'aaa', work: false}
                ],
                goods: [
                    {id: 1, name: 'a', tag: [0, 2], price: 10, img: require("../../assets/logo.png"), inventory: 100},
                    {id: 2, name: 'b', tag: [1, 2], price: 15, img: require("../../assets/logo.png"), inventory: 10},
                    {id: 3, name: 'c', tag: [0, 1, 2], price: 1, img: require("../../assets/logo.png"), inventory: 0},
                    {id: 4, name: 'd', tag: [2], price: 13, img: require("../../assets/logo.png"), inventory: 5},
                ],
                order: [
                    {id: 1, totalCost: 10, status: 0, time: "2020-7-5"},
                    {id: 2, totalCost: 230, status: 1, time: "2020-7-6"},
                    {id: 3, totalCost: 123, status: 2, time: "2020-7-7"}
                ],
                detail: {
                    id: 1,
                    status: 1,
                    data: [
                        {id: 1, gid: 1, count: 10, cost: 10},
                        {id: 2, gid: 2, count: 3, cost: 30},
                        {id: 3, gid: 3, count: 14, cost: 12}]
                },
                showDialog: false
            }
        },

        computed: {
            total() {
                let sum = 0
                for (let i = 0; i < this.detail.data.length; ++i) {
                    sum += this.detail.data[i].cost
                }
                return sum
            }
        },

        methods: {
            statusName(st) {
                if (st === 0) {
                    return "未付款"
                } else if (st === 1) {
                    return "已付款"
                } else if (st === 2) {
                    return "已送达"
                } else {
                    return null
                }
            },

            getDetail(id) {
                console.log(id)
                // TODO 链接数据库
                setTimeout(() => {
                    this.showDialog = true;
                }, 300)
            }
        }
    }
</script>

<style scoped>

</style>