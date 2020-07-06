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
                    <h1>
                        订单编号：{{detail.id}}
                    </h1>
                    <div class="background">
                        <div v-for="(o, index) in detail.data" :key="index">
                            <transition name="fade" appear>
                                <div class="item round">
                                    <h1>{{decodeURIComponent(goods[o.gid - 1].name)}}</h1>
                                    <!--suppress HtmlUnknownTarget -->
                                    <img :src="goods[o.gid - 1].img" alt="暂无图片">
                                    <div style="height: 20px">
                                        <template v-for="t in goods[o.gid - 1].tag">
                                            <el-tag v-if="tags[t - 1].work===true" :key="t" class="tag">
                                                {{tags[t - 1].tag}}
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
                    <el-button type="primary" round :disabled="detail.status !== 1" v-on:click="send">发货</el-button>
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
                tags: [],
                goods: [],
                order: [],
                detail: {},
                showDialog: false
            }
        },

        created() {
            fetch('http://119.3.172.223/vue/shopAPI/tags.php').then(response => response.json()).then(json => {
                if (json.errorCode !== 0) {
                    this.$message.error('系统异常，请联系管理员')
                    return
                }
                this.tags = json.data

                fetch('http://119.3.172.223/vue/shopAPI/goods/goods.php').then(response => response.json()).then(json => {
                    if (json.errorCode !== 0) {
                        this.$message.error('系统异常，请联系管理员')
                        return
                    }
                    this.goods = json.data

                    fetch('http://119.3.172.223/vue/shopAPI/orderAll.php')
                        .then(response => response.json()).then(json => {
                        if (json.errorCode !== 0) {
                            this.$message.error('系统异常，请联系管理员')
                            return
                        }
                        this.order = json.data
                    }).catch(() => {
                        this.$message.error('网络异常')
                    })
                }).catch(() => {
                    this.$message.error('网络异常')
                })
            }).catch(() => {
                this.$message.error('网络异常')
            })
        },

        computed: {
            total() {
                let sum = 0
                if (this.showDialog) {
                    for (let i = 0; i < this.detail.data.length; ++i) {
                        sum += this.detail.data[i].cost
                    }
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
                } else if (st === 3) {
                    return "已取消"
                }
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

            send() {
                fetch('http://119.3.172.223/vue/shopAPI/orderSend.php?id=' + this.detail.id).then(response => response.json()).then(json => {
                    if (json.errorCode !== 0) {
                        this.$message.error('系统异常，请联系管理员')
                        return
                    }
                    this.showDialog = false;
                    this.$emit('flush')
                    this.$message.success('发货成功');
                }).catch(() => {
                    this.$message.error('网络异常')
                })
            }
        }
    }
</script>

<style scoped>

</style>