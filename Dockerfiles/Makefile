SUBDIRS = 01_Build_Emdros 02_MySQL 90_BibleOnlineLearner

all: environment_variables.txt
	for d in $(SUBDIRS); do make -C $$d all ; done

clean:
	for d in $(SUBDIRS); do make -C $$d clean ; done

build: environment_variables.txt
	for d in $(SUBDIRS); do make -C $$d build ; done

run: environment_variables.txt
	for d in $(SUBDIRS); do make -C $$d run ; done

environment_variables.txt:
	echo "Do:"
	echo "cp environment_variables-dist.txt environment_variables.txt"
	echo "Then edit environment_variables.txt"


