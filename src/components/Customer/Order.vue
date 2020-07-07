<template>
    <transition name="el-fade-in-linear">
        <!--订单-->
        <div>
            <div class="background" v-if="order.length !== 0">
                <div v-for="(o, index) in order" :key="index"
                     v-bind:class="{'time-line': o.id === -1, 'card': o.id !== -1}">
                    <transition name="fade" v-if="clock >= index && o.id !== -1" appear>
                        <div class="item round-small">
                            <h1>编号：{{o.id}}</h1>
                            <p>{{o.time}}</p>
                            <!--suppress JSUnresolvedVariable -->
                            <h2>费用：{{o.totalCost}}</h2>
                            <p>状态：{{statusName(o.status)}}</p>
                            <el-button type="primary" round v-on:click="getDetail(o)">详情</el-button>
                        </div>
                    </transition>
                    <transition name="fade" v-if="clock >= index && o.id === -1" appear>
                        <el-alert
                                type="success"
                                center
                                :closable="false">
                            <h1>{{o.time}}</h1>
                        </el-alert>
                    </transition>
                </div>
            </div>
            <div v-else style="justify-content: center;">
                <h1 style="font-size: 50px; text-align: center;">空空如也</h1>
            </div>

            <el-dialog title="订单" :visible.sync="showDialog">
                <!--订单详情-->
                <div>
                    <h1>
                        订单编号：{{detail.id}}
                    </h1>
                    <div class="background background-dialog">
                        <div v-for="(o, index) in detail.data" :key="index" class="card">
                            <transition name="fade" appear>
                                <div class="item round">
                                    <h1>{{decodeURIComponent(goods[o.gid - 1].name)}}</h1>
                                    <!--suppress HtmlUnknownTarget -->
                                    <el-image :src="goods[o.gid - 1].img" alt="暂无图片" class="good-img" lazy></el-image>
                                    <div style="height: 20px">
                                        <template v-for="t in goods[o.gid - 1].tag">
                                            <el-tag v-if="tags[t - 1].work===true" :key="t" class="tag">
                                                {{tags[t - 1].tag}}
                                            </el-tag>
                                        </template>
                                    </div>

                                    <h2>数量：{{o.count}}</h2>
                                    <!--suppress JSUnresolvedVariable -->
                                    <p>总花费：{{o.cost}}</p>
                                </div>
                            </transition>
                        </div>
                    </div>
                    <h1>总计：{{total}}</h1>
                    <p>状态：{{statusName(detail.status)}}</p>
                    <el-button type="primary" round :disabled="detail.status !== 0" v-on:click="payFor()">付款</el-button>
                    <el-button type="danger" round :disabled="detail.status >= 2" v-on:click="cancelOrder()">取消订单
                    </el-button>
                </div>
            </el-dialog>
        </div>
    </transition>
</template>

<script>
    import {str2int} from '../../static/Main'
    import {ip} from '../../static/Ip'

    export default {
        name: "Order",
        data() {
            return {
                clock: 0,
                intervalId: null,
                order: [],
                tags: [],
                goods: [],
                detail: {},
                showDialog: false
            }
        },

        created() {
            fetch(ip + '/tags.php').then(response => response.json()).then(json => {
                if (json.errorCode !== 0) {
                    this.$message.error('系统异常，请联系管理员')
                    return
                }
                this.tags = json.data

                fetch(ip + '/goods/goods.php').then(response => response.json()).then(json => {
                    if (json.errorCode !== 0) {
                        this.$message.error('系统异常，请联系管理员')
                        return
                    }
                    this.goods = json.data

                    fetch(ip + '/deleteOrder.php?user=' + this.userId).then(() => {
                        fetch(ip + '/order.php?user=' + this.userId)
                            .then(response => response.json()).then(json => {
                            if (json.errorCode !== 0) {
                                this.$message.error('系统异常，请联系管理员')
                                return
                            }
                            json.data.reverse()
                            this.order = json.data

                            let timeStamp = [0, 86400000, 604800000, 2592000000, 9223372036854775807];
                            let flag = [false, false, false, false]
                            let str = ['24小时内', '最近7天', '最近一个月', '更早之前']
                            let curTime = new Date().getTime()

                            for (let i = 0; i < this.order.length; ++i) {
                                for (let j = 1; j < timeStamp.length; ++j) {
                                    if (curTime - str2int(this.order[i].time) < timeStamp[j]) {
                                        if (!flag[j - 1]) {
                                            this.order.splice(i, 0, {id: -1, time: str[j - 1]})
                                            flag[j - 1] = true
                                        }
                                        break
                                    }
                                }
                            }

                            this.intervalId = setInterval(() => {
                                this.clock++
                                if (this.clock > this.order.length + 1)
                                    clearInterval(this.intervalId)
                            }, 200);

                        }).catch(() => {
                            this.$message.error('网络异常')
                        })
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

        props: {
            userId: Number,
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
                return null
            },

            getDetail(o) {
                fetch(ip + '/detail.php?id=' + o.id).then(response => response.json()).then(json => {
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
                fetch(ip + '/payFor.php?id=' + this.detail.id).then(response => response.json()).then(json => {
                    if (json.errorCode !== 0) {
                        this.$message.error('系统异常，请联系管理员')
                        return
                    }
                    this.showDialog = false;
                    this.$emit('flush')
                    this.$message.success('付款成功');
                }).catch(() => {
                    this.$message.error('网络异常')
                })
            },

            cancelOrder() {
                fetch(ip + '/cancelOrder.php?id=' + this.detail.id).then(response => response.json()).then(json => {
                    if (json.errorCode !== 0) {
                        this.$message.error('系统异常，请联系管理员')
                        return
                    }
                    this.showDialog = false
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