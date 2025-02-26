import sys
import subprocess

# 自動安裝缺少的套件
def install_package(package):
    try:
        __import__(package)  # 嘗試匯入套件
    except ImportError:
        print(f"正在安裝 {package} ...")  # 提示正在安裝套件
        subprocess.check_call([sys.executable, "-m", "pip", "install", package])  # 使用 pip 安裝套件

# 確保 `requests` 和 `beautifulsoup4` 已安裝
install_package("requests")
install_package("beautifulsoup4")

# 安裝完成後再匯入
import requests
from bs4 import BeautifulSoup
import re
import csv
import os
from datetime import datetime

# 設定 CSV 檔案存放路徑（Windows 下載資料夾）
DOWNLOAD_FOLDER = os.path.join(os.path.expanduser("~"), "Downloads")

def get_psrid_list():
    """
    爬取學校列表，取得所有 psrid（學校代碼）。
    """
    list_url = "https://www.techadmi.edu.tw/comms.php?comid=comd06"  # 學校列表的 URL
    headers = {
        "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36"
    }

    response = requests.get(list_url, headers=headers)  # 發送 HTTP 請求
    if response.status_code != 200:
        print(f"無法請求 {list_url}，HTTP 狀態碼: {response.status_code}")  # 如果請求失敗，回報錯誤
        return []

    response.encoding = response.apparent_encoding  # 設定正確的編碼
    soup = BeautifulSoup(response.text, "html.parser")  # 解析 HTML

    psrid_set = set()  # 使用 set 來存學校代碼，避免重複
    for a in soup.find_all("a", href=True):  # 找出所有的超連結
        school_name = a.get_text(strip=True)  # 取得學校名稱
        if "全國技專校院" in school_name:
            continue  # 排除「全國技專校院」

        match = re.search(r"psrid=(\d+)", a["href"])  # 使用正則表達式擷取 psrid
        if match:
            psrid_set.add(match.group(1))  # 將 psrid 加入集合

    return sorted(list(psrid_set), key=int)  # 轉成排序後的列表返回

def scrape_school_data(psrid):
    """
    根據 psrid 爬取學校資訊（名稱、科系、地址、官網）。
    """
    url = f"https://www.techadmi.edu.tw/school.php?psrid={psrid}&ar=2"  # 學校資訊的 URL
    headers = {
        "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36"
    }

    response = requests.get(url, headers=headers)  # 發送 HTTP 請求
    if response.status_code != 200:
        print(f"無法請求 {url}，HTTP 狀態碼: {response.status_code}")  # 如果請求失敗，回報錯誤
        return []

    response.encoding = response.apparent_encoding  # 設定正確的編碼
    soup = BeautifulSoup(response.text, "html.parser")  # 解析 HTML

    print(f"成功請求 {url}")  # 請求成功時提示

    # 抓取學校名稱
    school_name = soup.find("h2").get_text(strip=True) if soup.find("h2") else "學校名稱未找到"

    # 避免「全國技專校院」被爬取
    if "全國技專校院" in school_name:
        print(f"跳過 psrid={psrid}，因為它是「{school_name}」")
        return []

    # 抓取地址
    address_tag = soup.find("p", class_="full-address")
    address = address_tag.get_text(strip=True) if address_tag else "地址未找到"

    # 抓取學校官網
    official_site = "官方網站未找到"
    official_link_tag = soup.select_one("div.side-block a.btn1[href]")
    if official_link_tag:
        official_site = official_link_tag["href"].strip()

    # 抓取科系列表
    department_list = [dep.get_text(strip=True) for dep in soup.select("div.phase-contents div.links3 a")]
    if not department_list:
        department_list.append("未找到科系")

    # 組合資料
    return [[school_name, dep, address, official_site] for dep in department_list]

def save_to_csv(data):
    """
    將爬取的學校資料寫入 CSV 檔案。
    """
    timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")  # 產生時間戳記
    filename = os.path.join(DOWNLOAD_FOLDER, f"school_data_{timestamp}.csv")  # 設定 CSV 檔名

    with open(filename, mode="w", encoding="utf-8-sig", newline="") as file:
        writer = csv.writer(file)
        writer.writerow(["學校名稱", "科系", "地址", "官方網站"])  # CSV 標題行
        writer.writerows(data)  # 寫入學校資料

    print(f"CSV 檔案已成功儲存: {filename}")  # 顯示存檔成功訊息

def main():
    all_data = []  # 存放所有學校的資料
    psrid_list = get_psrid_list()  # 取得所有學校代碼
    print("找到的學校代碼 (psrid)：", psrid_list)

    for psrid in psrid_list:  # 依序處理每間學校
        print(f"正在處理學校 psrid = {psrid} ...")
        school_data = scrape_school_data(psrid)  # 爬取學校資料
        all_data.extend(school_data)  # 加入總資料中

    # 顯示爬取結果
    print("\n最終結果：")
    for item in all_data:
        print("學校名稱:", item[0])
        print("科系:", item[1])
        print("地址:", item[2])
        print("官方網站:", item[3])
        print("-" * 50)

    if all_data:
        save_to_csv(all_data)  # 存入 CSV 檔案

if __name__ == "__main__":
    main()  # 執行主函式
