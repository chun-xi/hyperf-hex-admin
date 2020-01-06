# hyperf-hex-admin
基于hyperf开发的后台权限管理系统

# 安装
`composer install `
# 导入数据库
`hex.sql`
# 依赖
+ redis扩展（其实根本没用到，因为hyperf在注入协程redis的时候需要用到`\Redis`，所以需要安装扩展）
