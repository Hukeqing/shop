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
                            <el-button type="primary" round v-on:click="getDetail(o)">详情</el-button>
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
                    <el-button type="primary" round :disabled="detail.status !== 0" v-on:click="payFor()">付款</el-button>
                    <el-button type="danger" round :disabled="detail.status < 2" v-on:click="cancelOrder()">取消订单
                    </el-button>
                </div>
            </el-dialog>
        </div>
    </transition>
</template>

<script>
    export default {
        name: "Order",
        data() {
            return {
                order: [
                    {id: 1, totalCost: 10, status: 0, time: "2020-7-5"},
                    {id: 2, totalCost: 230, status: 1, time: "2020-7-6"},
                    {id: 3, totalCost: 123, status: 2, time: "2020-7-7"},
                    {id: 4, totalCost: 161, status: 3, time: "2020-7-8"}
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

        created() {
            fetch('http://119.3.172.223/vue/shopAPI/order.php').then(response => response.json()).then(json => {
                if (json.errorCode !== 0) {
                    this.$message.error('系统异常，请联系管理员')
                    return
                }
                this.order = json.data
            }).catch(() => {
                this.$message.error('网络异常')
            })
        },

        props: {
            tags: Array,
            goods: Array,
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
                /**
                 * 0 for make order
                 * 1 for paid
                 * 2 for send
                 * 3 for cancel
                 */
                if (st === 0) {
                    return "未付款"
                } else if (st === 1) {
                    return "已付款"
                } else if (st === 2) {
                    return "已送达"
                } else if (st === 3) {
                    return "已取消"
                }
                this.$message.error("系统故障，请联系管理员")
                return null
            },

            getDetail(o) {
                fetch('http://119.3.172.223/vue/shopAPI/detail.php?id=' + o.id).then(response => response.json()).then(json => {
                    if (json.errorCode !== 0) {
                        this.$message.error('系统异常，请联系管理员')
                        return
                    }
                    this.detail.id = o.id
                    this.detail.status = o.status
                    this.detail.data = json.data
                    this.showDialog = true;
                }).catch(() => {
                    this.$message.error('网络异常')
                })
            },

            payFor() {
                fetch('http://119.3.172.223/vue/shopAPI/payFor.php?id=' + this.detail.id).then(response => response.json()).then(json => {
                    if (json.errorCode !== 0) {
                        this.$message.error('系统异常，请联系管理员')
                        return
                    }
                    this.$emit('flush')
                    this.$message.success('付款成功');
                }).catch(() => {
                    this.$message.error('网络异常')
                })
            },

            cancelOrder() {
                fetch('http://119.3.172.223/vue/shopAPI/cancelOrder.php?id=' + this.detail.id).then(response => response.json()).then(json => {
                    if (json.errorCode !== 0) {
                        this.$message.error('系统异常，请联系管理员')
                        return
                    }
                    this.$emit('flush')
                    this.$message.success('取消成功');
                }).catch(() => {
                    this.$message.error('网络异常')
                })
            }
        }
    }
</script>

<style scoped>

</style>