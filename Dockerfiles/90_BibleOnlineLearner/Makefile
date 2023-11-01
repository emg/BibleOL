CLEANFILES = *~



all: 


pull:


build: emdros_deb/  environment_variables.txt
	podman build -t bible_online_learner --build-arg-file=./environment_variables.txt .


run: environment_variables.txt
	podman run --name emdros_build3 bible_online_learner 
	podman cp emdros_build3:/tmp/emdros_deb/ - | tar xfv -

run2: environment_variables.txt
	podman run --detach --name emdros_build2 --tty bible_online_learner /bin/bash


clean:
	rm -f $(CLEANFILES)


emdros_deb/:
	make -C ../Build_Emdros/ build run
	cp -Rp ../Build_Emdros/emdros_deb .

environment_variables.txt: ../environment_variables.txt
	cp $< $@

