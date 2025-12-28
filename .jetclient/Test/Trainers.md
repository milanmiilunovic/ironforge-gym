```toml
name = 'Trainers'
method = 'GET'
url = 'ALTER TABLE classes ADD COLUMN duration_minutes INT DEFAULT 60 AFTER schedule_time;'
sortWeight = 1000000
id = '3b144698-5272-4cbb-b9d6-3f8b6bd8734f'

[[headers]]
key = 'Authentication'
value = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyIjp7InVzZXJfaWQiOjgsImZ1bGxfbmFtZSI6IlVzZXIgQWNjb3VudCIsImVtYWlsIjoidXNlckBpcm9uZm9yZ2UuY29tIiwicm9sZSI6InVzZXIiLCJqb2luX2RhdGUiOiIyMDI1LTEyLTI2In0sImlhdCI6MTc2Njc0NTcwMSwiZXhwIjoxNzY2ODMyMTAxfQ.Ra4bV3cc3Xc73_a9yUKiTFksC8nU15m1RiH36WJsYnk'
disabled = true

[body]
type = 'JSON'
raw = '''
{
  "title": "Yoga Basic2s 3",
  "trainer_id": 1,
  "category_id": 2,
  "schedule_time": "2025-11-20 10:00:00",
  "capacity": 20,
  "description": "Beginner-friendly yoga session"
}'''
```
