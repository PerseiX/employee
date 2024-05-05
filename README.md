### How to install?
Please start application using docker compose up. after that go into container using `docker exec -it <container_id> bash`
Run `make install` from container. This command set up your production and test databases. Run `bin/console app:generate-remuneration-report` in order to report generation. Have a fun!

### Small input
The command first clear all reports and generate the new one. It can be improved in next phase. We can also keep old reports or generate them monthly. 
It can be done in next phases in order to performance improvements and some historical data.

### Bonus rule
When you want to add new bonus rule for department you have to create new calculation policy and configure bonus in database using json configuration. 
For example: `{"class": "\\App\\Payment\\Domain\\Policy\\PercentageBonusCalculationPolicy", "percent": 20}`

### What can be improved?
- Money object in domain
- UUID instead of int for domain objects
- Keep old reports and not delete all each time 
