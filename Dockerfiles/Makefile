SUBDIRS = 01_Build_Emdros 02_MySQL 90_BibleOnlineLearner

all: .env
	for d in $(SUBDIRS); do make -C $$d all ; done

clean:
	for d in $(SUBDIRS); do make -C $$d clean ; done

build: .env
	for d in $(SUBDIRS); do make -C $$d build ; done

run: .env
	for d in $(SUBDIRS); do make -C $$d run ; done

.env:
	echo "Do:"
	echo "cp environment_variables-dist.txt .env"
	echo "Then edit .env"


