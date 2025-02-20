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
    list_url = "https://www.techadmi.edu.tw/comms.php?comid=comd06"
    headers = {
        "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36"
    }

    response = requests.get(list_url, headers=headers)
    if response.status_code != 200:
        print(f"無法請求 {list_url}，HTTP 狀態碼: {response.status_code}")
        return []

    response.encoding = response.apparent_encoding
    soup = BeautifulSoup(response.text, "html.parser")

    psrid_set = set()
    for a in soup.find_all("a", href=True):
        school_name = a.get_text(strip=True)
        if "全國技專校院" in school_name:
            continue

        match = re.search(r"psrid=(\d+)", a["href"])
        if match:
            psrid_set.add(match.group(1))

    return sorted(list(psrid_set), key=int)

def scrape_school_data(psrid):
    """
    根據 psrid 爬取學校資訊（名稱、科系、地址、官網）。
    """
    url = f"https://www.techadmi.edu.tw/school.php?psrid={psrid}&ar=2"
    headers = {
        "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36"
    }

    response = requests.get(url, headers=headers)
    if response.status_code != 200:
        print(f"無法請求 {url}，HTTP 狀態碼: {response.status_code}")
        return []

    response.encoding = response.apparent_encoding
    soup = BeautifulSoup(response.text, "html.parser")

    print(f"成功請求 {url}")

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
    timestamp = datetime.now().strftime("%Y%m%d_%H%M%S")
    filename = os.path.join(DOWNLOAD_FOLDER, f"school_data_{timestamp}.csv")

    with open(filename, mode="w", encoding="utf-8-sig", newline="") as file:
        writer = csv.writer(file)
        writer.writerow(["學校名稱", "科系", "地址", "官方網站"])  # CSV 標題行
        writer.writerows(data)

    print(f"CSV 檔案已成功儲存: {filename}")
all_data = []
def main():
    # 1. 抓取所有學校代碼 (psrid)
    psrid_list = get_psrid_list()
    print("找到的學校代碼 (psrid)：", psrid_list)

    
    
    # 2. 爬取每間學校的資料
    for psrid in psrid_list:
        print(f"正在處理學校 psrid = {psrid} ...")
        school_data = scrape_school_data(psrid)
        all_data.extend(school_data)
    
    # 3. 顯示爬取結果
    print("\n最終結果：")
    for item in all_data:
        print("學校代碼:",{psrid})
        print("學校名稱:", item[0])
        print("科系:", item[1])
        print("地址:", item[2])
        print("官方網站:", item[3])
        print("-" * 50)

    # 4. 寫入 CSV
    if all_data:
        save_to_csv(all_data)

if __name__ == "__main__":
    main()
