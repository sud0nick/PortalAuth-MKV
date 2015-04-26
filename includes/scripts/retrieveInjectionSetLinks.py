import sys
import re
import requests
from bs4 import BeautifulSoup

# The location of the Injection Set URLs
url = "https://forums.hak5.org/index.php?/topic/34827-portal-auth-injection-sets/"

r = requests.get(url)
soup = BeautifulSoup(r.text, "html.parser")

for tag in soup.find_all('a'):
    if tag.has_attr('title'):
        if tag['title'] == "External link":
            if not "www.puffycode.com" in tag.text:
                print tag.text.strip()+";"+tag['href']