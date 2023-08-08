#!/bin/bash

# 设置测试目录路径
test_dir="./tests"

# 获取所有测试文件的列表
test_files=$(find "$test_dir" -name "*Test.php")

# 定义空数组来存储方法名称
declare -a method_names=()

# 遍历每个测试文件
for file in $test_files; do
    # 使用 grep 命令来查找所有以 "test" 开头的方法名
    current_methods=$(grep "function test" "$file" | sed -E 's/.*function ([^ ]+)\(.*/\1/')

    # 将找到的方法名添加到方法数组中
    method_names+=($current_methods)
done

# 展示方法列表和编号
for ((i=0; i<${#method_names[@]}; i++)); do
    echo "$i. ${method_names[$i]}"
done

# 提示用户输入编号
read -p "请输入要测试的方法编号: " selected_method_index

# 获取选中的方法名称
selected_method="${method_names[$selected_method_index]}"

# 执行 PHPUnit 测试
./vendor/bin/phpunit --filter "$selected_method"
