<template>
    <transition name="el-fade-in-linear">
        <div>
            <div class="background">
                <div v-for="tag in tags" :key="tag.id">
                    <transition name="fade" v-if="clock >= tag.id" appear>
                        <div class="item round-small">
                            <!--<el-tag class="tag">{{tag.tag}}</el-tag>-->
                            <el-input v-model="tag.tag" placeholder="请输入内容" size="mini" style="width: 100px"
                                      v-on:change="changeTagName(tag.id)"></el-input>
                            <el-switch
                                    style="margin-left: 10px"
                                    v-model="tag.work"
                                    active-color="#13ce66"
                                    inactive-color="#ff4949"
                                    v-on:change="changeTagStatus(tag.id)">
                            </el-switch>
                        </div>
                    </transition>
                </div>
            </div>
            <div>
                <p>
                    新建标签：
                    <el-input style="width: 200px; margin-right: 10px"
                              placeholder="请输入内容"
                              v-model="newTag"
                              clearable>
                    </el-input>
                    <el-button type="primary" round v-on:click="changeNewTag">添加</el-button>
                </p>
            </div>
        </div>
    </transition>
</template>

<script>
    export default {
        name: "TagManager",

        data() {
            return {
                clock: 1,
                intervalId: null,
                tags: [
                    {id: 1, tag: 'abc', work: true},
                    {id: 2, tag: 'def', work: true},
                    {id: 3, tag: 'aaa', work: false}
                ],
                newTag: "",
            }
        },

        created() {
            fetch('http://119.3.172.223/vue/shopAPI/tags.php').then(response => response.json()).then(json => {
                if (json.errorCode !== 0) {
                    this.$message.error('系统异常，请联系管理员')
                    return
                }
                this.tags = json.data

                this.intervalId = setInterval(() => {
                    this.clock++
                    if (this.clock > this.tags.length + 1)
                        clearInterval(this.intervalId)
                }, 200);

            }).catch(() => {
                this.$message.error('网络异常')
            })
        },

        methods: {
            tagFunc(work) {
                if (work) {
                    return "删除"
                } else {
                    return "恢复"
                }
            },

            changeTagStatus(index) {
                fetch('http://119.3.172.223/vue/shopAPI/tagStatus.php?id=' + index +
                    '&status=' + (this.tags[index - 1].work ? 1 : 0))
                    .then(response => response.json()).then(json => {
                    if (json.errorCode !== 0) {
                        this.$message.error('系统异常，请联系管理员')
                        return
                    }
                    this.$message.success('更新已保存')
                }).catch(() => {
                    this.$message.error('网络异常')
                })
            },

            changeTagName(index) {
                fetch('http://119.3.172.223/vue/shopAPI/tagChange.php?id=' + index +
                    '&name=' + this.tags[index - 1].tag)
                    .then(response => response.json()).then(json => {
                    if (json.errorCode !== 0) {
                        this.$message.error('系统异常，请联系管理员')
                        return
                    }
                    this.$message.success('更新已保存')
                }).catch(() => {
                    this.$message.error('网络异常')
                })
            },

            changeNewTag() {
                this.newTag = this.newTag.replace(/\s*/g, "")
                if (this.newTag.length === 0) {
                    this.$message.error('请输入至少一个字符')
                    return
                }
                fetch('http://119.3.172.223/vue/shopAPI/tagNew.php?name=' + this.newTag)
                    .then(response => response.json()).then(json => {
                    if (json.errorCode !== 0) {
                        this.$message.error('系统异常，请联系管理员')
                        return
                    }
                    this.$message.success('更新已保存')
                    this.$emit('flush')
                }).catch(() => {
                    this.$message.error('网络异常')
                })
            }
        }
    }
</script>

<style scoped>

</style>