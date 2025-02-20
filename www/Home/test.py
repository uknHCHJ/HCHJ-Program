import requests
from bs4 import BeautifulSoup
import re
import mysql.connector

def get_psrid_list():
    """
    從學校列表頁面自動抓取所有 psrid（學校代碼），並排除「全國技專校院」。
    """
    list_url = "https://www.techadmi.edu.tw/comms.php?comid=comd06"
    headers = {
        "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36"
    }

    response = requests.get(list_url, headers=headers)
    if response.status_code != 200:
        print(f"❌ 無法請求 {list_url}，HTTP 狀態碼: {response.status_code}")
        return []

    response.encoding = response.apparent_encoding
    soup = BeautifulSoup(response.text, "html.parser")

    psrid_set = set()
    
    # 遍歷所有 a 標籤
    for a in soup.find_all("a", href=True):
        school_name = a.get_text(strip=True)
        
        # 先排除掉含有「全國技專校院」的連結（如果 a 標籤文字剛好含此字串）
        if "全國技專校院" in school_name:
            continue

        match = re.search(r"psrid=(\d+)", a["href"])
        if match:
            psrid = match.group(1)
            psrid_set.add(psrid)

    return sorted(list(psrid_set), key=int)

def scrape_school_data(psrid):
    """
    根據 psrid 抓取學校名稱、科系、地址、官網網址。
    """
    url = f"https://www.techadmi.edu.tw/school.php?psrid={psrid}&ar=2"
    headers = {
        "User-Agent": "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36"
    }
    
    response = requests.get(url, headers=headers)
    if response.status_code != 200:
        print(f"❌ 無法請求 {url}，HTTP 狀態碼: {response.status_code}")
        return []

    response.encoding = response.apparent_encoding
    soup = BeautifulSoup(response.text, "html.parser")

    print(f"✅ 成功請求 {url}")

    # 抓取學校名稱
    school_title_tag = soup.find("h2")
    school_name = school_title_tag.get_text(strip=True) if school_title_tag else "❌ 學校名稱未找到"

    # 如果是「全國技專校院」，就直接跳過
    if "全國技專校院" in school_name:
        print(f"🚫 跳過 psrid={psrid}，因為它是「{school_name}」")
        return []

    # 抓取地址
    address_list = []
    address_tag = soup.find("p", class_="full-address")
    if address_tag:
        for br in address_tag.find_all("br"):
            br.replace_with("\n")
        address_list = address_tag.get_text("\n", strip=True).split("\n")
    address = " / ".join(address_list) if address_list else "❌ 地址未找到"

    # 抓取學校官網
    official_site = "❌ 官方網站未找到"
    official_link_tag = soup.select_one("div.side-block a.btn1[href]")
    if official_link_tag:
        official_site = official_link_tag["href"].strip()

    # 抓取科系列表
    department_list = []
    dep_tags = soup.select("div.phase-contents div.links3 a")
    for dep_tag in dep_tags:
        department_list.append(dep_tag.get_text(strip=True))
    if not department_list:
        department_list.append("❌ 未找到科系")

    # 組合結果
    results = []
    for dep in department_list:
        results.append([school_name, dep, address, official_site])

    return results
def insert_into_db(data):
    """
    將最終結果寫入 MySQL 資料庫。
    data 為一個 list，其中每個元素都是 [school_name, department, address, official_site]。
    """
    try:
        connection = mysql.connector.connect(
            host="127.0.0.1",
            user="HCHJ",
            password="xx435kKHq",
            database="HCHJ",
            charset="utf8mb4"  # 指定編碼
        )
        cursor = connection.cursor()
        insert_query = """
        INSERT INTO test (school_name, department, address, official_site)
        VALUES (%s, %s, %s, %s)
        """
        # 使用 executemany 一次性插入多筆資料
        cursor.executemany(insert_query, data)
        connection.commit()
        print("✅ 資料已成功寫入資料庫！")
    except mysql.connector.Error as err:
        print("❌ 資料庫錯誤:", err)
    finally:
        if connection.is_connected():
            cursor.close()
            connection.close()

def main():
    # 1. 抓取所有 psrid
    psrid_list = get_psrid_list()
    print("✅ 找到的學校代碼 (psrid)：", psrid_list)

    all_data = []
    
    # 2. 遍歷所有 psrid，爬取學校資訊
    for psrid in psrid_list:
        print(f"📌 正在處理學校 psrid = {psrid} ...")
        school_data = scrape_school_data(psrid)
        all_data.extend(school_data)
    
    # 3. 輸出結果 (可先在 console 印出確認)
    print("\n📌 最終結果：")
    for item in all_data:
        print("學校名稱:", item[0])
        print("科系:", item[1])
        print("地址:", item[2])
        print("官方網站:", item[3])
        print("-" * 50)

    # 4. 寫入資料庫
    if all_data:
        insert_into_db(all_data)

if __name__ == "__main__":
    main()
