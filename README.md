# CI4 Recruitment Platform

## 📋 프로젝트 개요
`ci4-recruitment-platform`은 CodeIgniter 4 프레임워크를 사용하여 개발된 **채용 플랫폼 초안 프로젝트**입니다.  
구인/구직 게시판, 회원 관리, 기본적인 검색 필터와 같은 주요 기능을 제공합니다.  
현재 프로젝트는 **초안 단계**로, 추후 확장을 염두에 두고 설계되었습니다.

---

## ⚙️ 기술 스택
- **프레임워크**: CodeIgniter 4
- **언어**: PHP 7.3 이상
- **운영체제**: Ubuntu
- **웹서버**: Nginx
- **데이터베이스**: MySQL

---

## 🛠 주요 기능
- 기본적인 구인/구직 게시판 기능 제공.
- 회원 관리 시스템 구현.
- CodeIgniter 4의 MVC 패턴을 활용한 모듈화된 설계.

---

## 📂 프로젝트 구성
### 주요 디렉토리 구조
```plaintext
/project-root
├── /app
│   ├── /Controllers  # HTTP 요청 처리
│   ├── /Models       # 데이터베이스와 상호작용
│   ├── /Helpers      # 자주사용하는 함수 
│   ├── /Views        # view
├── /public           # 공개 디렉토리
├── /writable         # 캐시 및 로그 저장
├── /system           # CodeIgniter 코어 파일