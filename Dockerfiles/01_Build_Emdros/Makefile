CLEANFILES = *~



all:


build:
	podman build -t bible_online_learner .

run:
	podman run --name emdros_build3 bible_online_learner 
	podman cp emdros_build3:/tmp/emdros_deb/ - | tar xfv -

run2:
	podman run --detach --name emdros_build2 --tty bible_online_learner /bin/bash
	podman cp emdros_build2:/tmp/emdros_deb/ - | tar xfv -

clean:
	rm -f $(CLEANFILES)
