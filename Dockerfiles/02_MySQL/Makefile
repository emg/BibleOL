CLEANFILES = *~



all:  pull build run


pull:
	podman pull mysql/mysql-server:latest

build: 


run:
	podman run -it --network some-network --rm mysql mysql -hsome-mysql -uexample-user -p

clean:
	rm -f $(CLEANFILES)

