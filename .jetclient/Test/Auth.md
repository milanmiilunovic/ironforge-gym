```toml
name = 'Auth'
method = 'GET'
url = 'http://localhost/ironforge-gym/backend/user/info/3'
sortWeight = 2000000
id = '62a8a752-435c-40a7-8800-1473d4931749'

[[queryParams]]
key = 'password'
value = 'Password123!'
disabled = true

[[queryParams]]
key = 'email'
value = 'test@gmail.com'
disabled = true

[[headers]]
key = 'Authorization'
value = 'eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJ1c2VyIjp7InVzZXJfaWQiOjksImZ1bGxfbmFtZSI6ImFkbWluIEFjY291bnQiLCJlbWFpbCI6ImFkbWluQGlyb25mb3JnZS5jb20iLCJyb2xlIjoiYWRtaW4iLCJqb2luX2RhdGUiOiIyMDI1LTEyLTI2In0sImlhdCI6MTc2Njc1NDU0MCwiZXhwIjoxNzY2ODQwOTQwfQ.U3L7n4mmfdEmmPTEoU2Za9ZI_guCpwfEY_QfeA9bGQ8'

[body]
type = 'JSON'
raw = '''
{
  "email": "user@ironforge.com",
  "password": "Test123!"
}'''
```
