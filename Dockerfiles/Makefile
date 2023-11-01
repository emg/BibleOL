SUBDIRS = 01_Build_Emdros

all:
	for d in $(SUBDIRS); do make -C $$d all ; done

clean:
	for d in $(SUBDIRS); do make -C $$d clean ; done

build:
	for d in $(SUBDIRS); do make -C $$d build ; done

run:
	for d in $(SUBDIRS); do make -C $$d run ; done


